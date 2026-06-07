<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class WeeklyBrief extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * @param  array<int, array{hook: string, why: string}>  $angles
     */
    public function __construct(
        public readonly Subscriber $subscriber,
        public readonly array $angles,
        public readonly string $weekOf,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: sprintf('Your Monday content brief — %s in %s', $this->subscriber->niche, $this->subscriber->language),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.weekly-brief',
            with: [
                'subscriber' => $this->subscriber,
                'angles' => $this->angles,
                'weekOf' => $this->weekOf,
                'unsubscribeUrl' => route('unsubscribe', $this->subscriber->unsubscribe_token),
                'referralUrl' => route('referral', $this->subscriber->id),
            ],
        );
    }
}
