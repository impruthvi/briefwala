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

final class ConfirmSubscription extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Subscriber $subscriber) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirm your BriefWala subscription',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.confirm-subscription',
            with: [
                'confirmUrl' => route('confirm', $this->subscriber->confirm_token),
                'subscriber' => $this->subscriber,
            ],
        );
    }
}
