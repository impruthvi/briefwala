<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Confirm your BriefWala subscription</title>
</head>
<body style="margin:0;padding:0;background-color:#f0ede5;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="background-color:#f0ede5;">
  <tr>
    <td align="center" style="padding:40px 16px;">

      <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="max-width:540px;width:100%;">

        {{-- Header --}}
        <tr>
          <td style="background-color:#F4730E;border-radius:12px 12px 0 0;padding:28px 36px 26px;">
            <p style="margin:0;font-size:24px;font-weight:700;color:#ffffff;letter-spacing:-0.03em;line-height:1.2;">Brief<span style="color:rgba(255,255,255,0.7);">Wala</span></p>
          </td>
        </tr>

        {{-- Body --}}
        <tr>
          <td style="background-color:#ffffff;padding:36px 36px 32px;border-left:1px solid #e4e1d8;border-right:1px solid #e4e1d8;">

            <p style="margin:0 0 10px;font-size:22px;font-weight:700;color:#1b1b18;letter-spacing:-0.02em;line-height:1.3;">You&rsquo;re almost in!</p>
            <p style="margin:0 0 28px;font-size:15.5px;color:#76746c;line-height:1.55;">
              Click the button below to confirm your subscription and get your first brief next Monday.
            </p>

            {{-- Details --}}
            <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="background-color:#faf9f7;border:1px solid #e4e1d8;border-radius:8px;margin-bottom:28px;">
              <tr>
                <td style="padding:16px 20px;">
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
                    <tr>
                      <td style="padding:4px 0;">
                        <p style="margin:0;font-size:13px;color:#a3a097;font-weight:600;letter-spacing:0.04em;text-transform:uppercase;">Niche</p>
                        <p style="margin:3px 0 0;font-size:14.5px;font-weight:600;color:#1b1b18;">{{ $subscriber->niche }}</p>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:10px 0 4px;border-top:1px solid #f0ede5;">
                        <p style="margin:0;font-size:13px;color:#a3a097;font-weight:600;letter-spacing:0.04em;text-transform:uppercase;">Platform</p>
                        <p style="margin:3px 0 0;font-size:14.5px;font-weight:600;color:#1b1b18;">{{ $subscriber->platform }}</p>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:10px 0 4px;border-top:1px solid #f0ede5;">
                        <p style="margin:0;font-size:13px;color:#a3a097;font-weight:600;letter-spacing:0.04em;text-transform:uppercase;">Language</p>
                        <p style="margin:3px 0 0;font-size:14.5px;font-weight:600;color:#1b1b18;">{{ $subscriber->language }}</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            {{-- CTA Button --}}
            <table cellpadding="0" cellspacing="0" border="0" role="presentation">
              <tr>
                <td style="border-radius:8px;background-color:#F4730E;">
                  <a href="{{ $confirmUrl }}" style="display:inline-block;padding:14px 28px;background-color:#F4730E;color:#ffffff;font-size:15px;font-weight:700;text-decoration:none;border-radius:8px;letter-spacing:-0.01em;">Confirm my subscription</a>
                </td>
              </tr>
            </table>

            <p style="margin:20px 0 0;font-size:13px;color:#a3a097;line-height:1.5;">This link is single-use. If you didn&rsquo;t sign up, you can safely ignore this email.</p>

          </td>
        </tr>

        {{-- Footer --}}
        <tr>
          <td style="background-color:#f5f4f0;border-radius:0 0 12px 12px;border:1px solid #e4e1d8;border-top:none;padding:16px 36px;">
            <p style="margin:0;font-size:12px;color:#a3a097;line-height:1.6;">briefwala.com &mdash; Weekly content ideas for Indian creators.</p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
