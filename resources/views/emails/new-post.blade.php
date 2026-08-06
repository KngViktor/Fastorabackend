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
                <p style="margin:0 0 8px;color:#5B6472;font-size:13px;text-transform:uppercase;letter-spacing:0.04em;">New on the Fastora Journal</p>
                <h1 style="margin:0 0 16px;font-size:22px;line-height:1.3;">{{ $post->title }}</h1>
                @if ($excerpt)
                  <p style="margin:0 0 24px;line-height:1.6;color:#5B6472;">{{ $excerpt }}</p>
                @endif
                <a href="{{ $url }}" style="display:inline-block;background:#2B7FD6;color:#ffffff;text-decoration:none;padding:12px 26px;border-radius:10px;font-weight:600;">
                  Read the full post
                </a>
              </td>
            </tr>
          </table>
          <p style="margin:20px 0 0;font-size:12px;color:#9aa3b2;">
            You're receiving this because you subscribed to the Fastora Journal.
            <a href="{{ $unsubscribeUrl }}" style="color:#9aa3b2;text-decoration:underline;">Unsubscribe</a>
          </p>
        </td>
      </tr>
    </table>
  </body>
</html>
