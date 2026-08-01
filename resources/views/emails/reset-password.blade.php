<!doctype html>
<html>
  <body style="margin:0;background:#f4f6fb;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:32px 0;">
      <tr>
        <td align="center">
          <table width="480" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e3e8ef;">
            <tr>
              <td style="background:#0B2545;padding:22px 32px;">
                <span style="color:#ffffff;font-size:20px;font-weight:700;letter-spacing:-0.02em;">Fastora</span>
              </td>
            </tr>
            <tr>
              <td style="padding:32px;">
                <h1 style="margin:0 0 12px;font-size:20px;">Reset your password</h1>
                <p style="margin:0 0 24px;line-height:1.6;color:#5B6472;">
                  We received a request to reset the password for your Fastora admin account.
                  Click the button below to choose a new password. This link expires in 60 minutes.
                </p>
                <a href="{{ $url }}" style="display:inline-block;background:#2B7FD6;color:#ffffff;text-decoration:none;padding:12px 26px;border-radius:10px;font-weight:600;">
                  Reset password
                </a>
                <p style="margin:24px 0 0;line-height:1.6;color:#5B6472;font-size:13px;">
                  If you didn't request this, you can safely ignore this email — your password won't change.
                </p>
                <p style="margin:16px 0 0;line-height:1.6;color:#9aa3b2;font-size:12px;word-break:break-all;">
                  Or paste this link into your browser:<br>{{ $url }}
                </p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
