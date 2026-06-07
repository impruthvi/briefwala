<x-mail::message>
# Your Monday Content Brief

**Niche:** {{ $subscriber->niche }} &nbsp;|&nbsp; **Language:** {{ $subscriber->language }} &nbsp;|&nbsp; **Week of:** {{ $weekOf }}

Here are 5 content angles researched for you this week:

---

@foreach ($angles as $i => $angle)
**{{ $i + 1 }}. {{ $angle['hook'] }}**

*{{ $angle['why'] }}*

---

@endforeach

**Hit reply** and tell us which angle you used this week. We read every reply.

---

Share BriefWala with a fellow creator and get a shoutout:
[Your referral link →]({{ $referralUrl }})

<x-mail::subcopy>
You're receiving this because you subscribed at briefwala.com.
[Unsubscribe]({{ $unsubscribeUrl }})
</x-mail::subcopy>
</x-mail::message>
