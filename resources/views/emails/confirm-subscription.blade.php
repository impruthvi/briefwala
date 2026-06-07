<x-mail::message>
# Confirm your subscription

You're almost in! Click the button below to activate your BriefWala brief.

Your first brief arrives **Monday morning** — {{ $subscriber->niche }} content ideas for {{ $subscriber->platform }}, in {{ $subscriber->language }}.

<x-mail::button :url="$confirmUrl" color="primary">
Confirm my subscription
</x-mail::button>

This link expires once used. If you didn't sign up, ignore this email.

Thanks,<br>
**BriefWala**
</x-mail::message>
