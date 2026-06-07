<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Your Monday Content Brief</title>
</head>
<body style="margin:0;padding:0;background-color:#f0ede5;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="background-color:#f0ede5;">
  <tr>
    <td align="center" style="padding:40px 16px;">

      <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="max-width:580px;width:100%;">

        {{-- Header --}}
        <tr>
          <td style="background-color:#F4730E;border-radius:12px 12px 0 0;padding:28px 36px 26px;">
            <p style="margin:0 0 2px;font-size:24px;font-weight:700;color:#ffffff;letter-spacing:-0.03em;line-height:1.2;">Brief<span style="color:rgba(255,255,255,0.7);">Wala</span></p>
            <p style="margin:10px 0 0;font-size:14px;font-weight:500;color:rgba(255,255,255,0.85);line-height:1.4;">Your Monday content brief &mdash; {{ $subscriber->niche }} &bull; {{ $subscriber->language }}</p>
          </td>
        </tr>

        {{-- Week label --}}
        <tr>
          <td style="background-color:#ffffff;padding:22px 36px 0;border-left:1px solid #e4e1d8;border-right:1px solid #e4e1d8;">
            <p style="margin:0;font-size:12.5px;font-weight:600;color:#a3a097;letter-spacing:0.05em;text-transform:uppercase;">Week of {{ $weekOf }}</p>
          </td>
        </tr>

        {{-- Intro --}}
        <tr>
          <td style="background-color:#ffffff;padding:14px 36px 22px;border-left:1px solid #e4e1d8;border-right:1px solid #e4e1d8;">
            <p style="margin:0;font-size:15.5px;font-weight:500;color:#1b1b18;line-height:1.5;">Here are {{ count($angles) }} content angles researched for you this week:</p>
          </td>
        </tr>

        {{-- Angles --}}
        @foreach ($angles as $i => $angle)
        <tr>
          <td style="background-color:#ffffff;padding:0 36px {{ $loop->last ? '28px' : '0' }};border-left:1px solid #e4e1d8;border-right:1px solid #e4e1d8;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="background-color:#faf9f7;border:1px solid #e4e1d8;border-radius:8px;margin-bottom:{{ $loop->last ? '0' : '12px' }};">
              <tr>
                <td style="padding:18px 20px;">
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
                    <tr>
                      <td width="36" valign="top" style="padding-right:14px;padding-top:1px;">
                        <table cellpadding="0" cellspacing="0" border="0" role="presentation">
                          <tr>
                            <td width="28" height="28" align="center" style="background-color:#F4730E;border-radius:50%;font-size:12.5px;font-weight:700;color:#ffffff;line-height:28px;text-align:center;">{{ $i + 1 }}</td>
                          </tr>
                        </table>
                      </td>
                      <td valign="top">
                        <p style="margin:0 0 6px;font-size:15.5px;font-weight:700;color:#1b1b18;line-height:1.4;letter-spacing:-0.01em;">{{ $angle['hook'] }}</p>
                        <p style="margin:0;font-size:14px;color:#76746c;line-height:1.6;">{{ $angle['why'] }}</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        @endforeach

        {{-- Reply CTA --}}
        <tr>
          <td style="background-color:#fff8f3;padding:20px 36px;border-left:1px solid #e4e1d8;border-right:1px solid #e4e1d8;border-top:1px solid #f0ede5;">
            <p style="margin:0;font-size:14.5px;color:#1b1b18;line-height:1.55;"><strong style="font-weight:700;">Which angle will you use this week?</strong><br>Hit reply and tell us. We read every reply.</p>
          </td>
        </tr>

        {{-- Referral --}}
        <tr>
          <td style="background-color:#ffffff;padding:20px 36px 22px;border-left:1px solid #e4e1d8;border-right:1px solid #e4e1d8;border-top:1px solid #f0ede5;">
            <p style="margin:0 0 14px;font-size:14px;color:#76746c;line-height:1.5;">Know a fellow creator? Share BriefWala and get a shoutout.</p>
            <a href="{{ $referralUrl }}" style="display:inline-block;background-color:#1b1b18;color:#ffffff;font-size:13.5px;font-weight:600;text-decoration:none;padding:10px 20px;border-radius:6px;letter-spacing:-0.01em;">Share BriefWala &rarr;</a>
          </td>
        </tr>

        {{-- Footer --}}
        <tr>
          <td style="background-color:#f5f4f0;border-radius:0 0 12px 12px;border:1px solid #e4e1d8;border-top:none;padding:16px 36px;">
            <p style="margin:0;font-size:12px;color:#a3a097;line-height:1.6;">
              You subscribed at briefwala.com. &nbsp;&bull;&nbsp;
              <a href="{{ $unsubscribeUrl }}" style="color:#76746c;text-decoration:underline;">Unsubscribe</a>
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
