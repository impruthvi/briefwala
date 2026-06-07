<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Brief\FetchTrendingTopics;
use App\Actions\Brief\GenerateBrief;
use App\Actions\Brief\SendBriefToSubscriber;
use App\Enums\SendStatus;
use App\Models\SendLog;
use App\Models\Subscriber;
use Carbon\CarbonImmutable;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;
use Throwable;

#[Signature('brief:send')]
#[Description('Send weekly content briefs to all confirmed subscribers')]
final class SendBriefsCommand extends Command
{
    public function __construct(
        private readonly FetchTrendingTopics $fetchTrendingTopics,
        private readonly GenerateBrief $generateBrief,
        private readonly SendBriefToSubscriber $sendBriefToSubscriber,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $weekOf = Date::now('Asia/Kolkata')->startOfWeek();

        $this->cleanUpStuckRows($weekOf);

        $subscribers = Subscriber::query()
            ->whereNotNull('confirmed_at')
            ->whereNull('unsubscribed_at')
            ->get();

        if ($subscribers->isEmpty()) {
            $this->alertAdmin('No confirmed subscribers found — brief:send skipped.');

            return self::SUCCESS;
        }

        $groups = $subscribers->groupBy(fn (Subscriber $s): string => sprintf('%s|%s', $s->niche, $s->language));

        $totalSent = 0;
        $totalFailed = 0;
        $skippedGroups = 0;

        foreach ($groups as $key => $groupSubscribers) {
            [$niche, $language] = explode('|', $key);

            $platform = (string) ($groupSubscribers->groupBy('platform')->sortByDesc->count()->keys()->first() ?? 'Both');

            try {
                $topics = $this->fetchTrendingTopics->handle($niche, $language);
                $angles = $this->generateBrief->handle($topics, $niche, $language, $platform);
            } catch (Throwable $e) {
                $this->error(sprintf('GPT failed for %s/%s: %s', $niche, $language, $e->getMessage()));
                $skippedGroups++;

                continue;
            }

            $subscriberIds = $groupSubscribers->pluck('id')->all();
            $weekOfStr = $weekOf->toDateString();

            $sentBefore = array_flip(
                SendLog::query()
                    ->whereIn('subscriber_id', $subscriberIds)
                    ->whereDate('week_of', $weekOfStr)
                    ->where('status', SendStatus::Sent)
                    ->pluck('subscriber_id')
                    ->all()
            );

            foreach ($groupSubscribers as $subscriber) {
                $this->sendBriefToSubscriber->handle($subscriber, $angles, $weekOf);
            }

            $sentAfter = array_flip(
                SendLog::query()
                    ->whereIn('subscriber_id', $subscriberIds)
                    ->whereDate('week_of', $weekOfStr)
                    ->where('status', SendStatus::Sent)
                    ->pluck('subscriber_id')
                    ->all()
            );

            $failedAfter = array_flip(
                SendLog::query()
                    ->whereIn('subscriber_id', $subscriberIds)
                    ->whereDate('week_of', $weekOfStr)
                    ->where('status', SendStatus::Failed)
                    ->pluck('subscriber_id')
                    ->all()
            );

            foreach ($groupSubscribers as $subscriber) {
                $id = $subscriber->id;

                if (! isset($sentBefore[$id]) && isset($sentAfter[$id])) {
                    $totalSent++;
                } elseif (! isset($sentAfter[$id]) && isset($failedAfter[$id])) {
                    $totalFailed++;
                }
            }
        }

        $this->info(sprintf('brief:send complete — sent: %d, failed: %d, skipped groups: %d', $totalSent, $totalFailed, $skippedGroups));

        if ($totalSent === 0) {
            $this->alertAdmin('brief:send ran but 0 emails were sent.');
        }

        $total = $totalSent + $totalFailed;
        if ($total > 0 && ($totalFailed / $total) >= 0.20) {
            $this->alertAdmin(sprintf('brief:send failure rate %d/%d exceeds 20%%.', $totalFailed, $total));
        }

        if ($skippedGroups > 0) {
            $this->alertAdmin($skippedGroups.' niche/language groups were skipped due to GPT failures.');
        }

        return self::SUCCESS;
    }

    private function cleanUpStuckRows(CarbonImmutable $weekOf): void
    {
        SendLog::query()
            ->whereDate('week_of', $weekOf->toDateString())
            ->where('status', SendStatus::Sending)
            ->where('created_at', '<', now()->subHours(6))
            ->update(['status' => SendStatus::Failed]);
    }

    private function alertAdmin(string $message): void
    {
        $adminEmail = config('mail.admin_email');

        if (empty($adminEmail)) {
            $this->warn('Admin alert skipped (ADMIN_EMAIL not set): '.$message);

            return;
        }

        Mail::raw('BriefWala alert:

'.$message, function ($mail) use ($adminEmail): void {
            $mail->to($adminEmail)->subject('BriefWala — brief:send alert');
        });
    }
}
