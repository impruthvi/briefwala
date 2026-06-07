BriefWala — Your Monday Content Brief
{{ $subscriber->niche }} · {{ $subscriber->language }} · Week of {{ $weekOf }}
================================================================

Here are {{ count($angles) }} content angles researched for you this week:

@foreach ($angles as $i => $angle)
{{ $i + 1 }}. {{ $angle['hook'] }}
   {{ $angle['why'] }}

@endforeach
----------------------------------------------------------------
Which angle will you use this week? Hit reply and tell us.
We read every reply.
----------------------------------------------------------------

Know a fellow creator? Share BriefWala:
{{ $referralUrl }}

----------------------------------------------------------------
You subscribed at briefwala.com.
Unsubscribe: {{ $unsubscribeUrl }}
