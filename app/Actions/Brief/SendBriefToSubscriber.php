<?php

declare(strict_types=1);

namespace App\Actions\Brief;

use App\Enums\SendStatus;
use App\Mail\WeeklyBrief;
use App\Models\SendLog;
use App\Models\Subscriber;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Mail;
use Throwable;

final readonly class SendBriefToSubscriber
{
    /**
     * @param  array<int, array{hook: string, why: string}>  $angles
     */
    public function handle(Subscriber $subscriber, array $angles, CarbonImmutable $weekOf): void
    {
        $weekOfDate = $weekOf->toDateString();

        $alreadySent = SendLog::query()
            ->where('subscriber_id', $subscriber->id)
            ->whereDate('week_of', $weekOfDate)
            ->where('status', SendStatus::Sent)
            ->exists();

        if ($alreadySent) {
            return;
        }

        $existing = SendLog::query()
            ->where('subscriber_id', $subscriber->id)
            ->whereDate('week_of', $weekOfDate)
            ->first();

        if (! $existing) {
            SendLog::query()->create([
                'subscriber_id' => $subscriber->id,
                'week_of' => $weekOfDate,
                'status' => SendStatus::Sending,
            ]);
        }

        try {
            Mail::to($subscriber->email)->send(
                new WeeklyBrief($subscriber, $angles, $weekOf->format('d M Y')),
            );

            SendLog::query()
                ->where('subscriber_id', $subscriber->id)
                ->whereDate('week_of', $weekOfDate)
                ->update([
                    'status' => SendStatus::Sent,
                    'sent_at' => now(),
                ]);
        } catch (Throwable) {
            SendLog::query()
                ->where('subscriber_id', $subscriber->id)
                ->whereDate('week_of', $weekOfDate)
                ->update(['status' => SendStatus::Failed]);
        }
    }
}
