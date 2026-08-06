<!doctype html>
<html>
  <body style="margin:0;background:#f4f6fb;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:32px 0;">
      <tr>
        <td align="center">
          <table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e3e8ef;">
            <tr>
              <td style="background:#0B2545;padding:22px 32px;">
                <span style="color:#ffffff;font-size:20px;font-weight:700;letter-spacing:-0.02em;">Fastora</span>
              </td>
            </tr>
            <tr>
              <td style="padding:32px;">
                <h1 style="margin:0 0 20px;font-size:20px;">
                  {{ $inquiry->kind === 'consultation' ? 'New consultation request' : 'New enquiry' }}
                </h1>

                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;line-height:1.6;">
                  @foreach ($fields as $label => $value)
                    <tr>
                      <td style="padding:4px 0;color:#5B6472;width:140px;vertical-align:top;">{{ $label }}</td>
                      <td style="padding:4px 0;color:#111827;">{{ $value }}</td>
                    </tr>
                  @endforeach
                </table>

                <p style="margin:24px 0 8px;color:#5B6472;font-size:13px;text-transform:uppercase;letter-spacing:0.04em;">
                  {{ $inquiry->kind === 'consultation' ? 'What they told us' : 'Brief' }}
                </p>
                <p style="margin:0;line-height:1.6;white-space:pre-wrap;">{{ $inquiry->brief }}</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
