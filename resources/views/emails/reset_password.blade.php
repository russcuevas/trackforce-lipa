<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TrackForce Lipa — Password Reset</title>
</head>

<body style="margin:0;padding:24px;background:#f8fafc;font-family:Arial,sans-serif;color:#1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
        style="max-width:560px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">
        <tr>
            <td style="background:#0B3D91;color:#ffffff;padding:18px 24px;font-size:20px;font-weight:700;">
                TrackForce Lipa
            </td>
        </tr>
        <tr>
            <td style="padding:24px;">
                <p style="margin:0 0 12px 0;font-size:15px;">
                    Hello, <strong>{{ $name }}</strong>.
                </p>
                <p style="margin:0 0 16px 0;font-size:14px;color:#374151;">
                    We received a password reset request for your investigator account. Click the button below to set a
                    new password.
                </p>

                <a href="{{ $url }}"
                    style="display:inline-block;background:#0B3D91;color:#ffffff;text-decoration:none;font-weight:700;font-size:13px;padding:11px 24px;border-radius:8px;letter-spacing:0.5px;">
                    RESET PASSWORD
                </a>

                <p style="margin:16px 0 8px 0;font-size:13px;color:#4b5563;">
                    This link will expire in <strong>60 minutes</strong>.
                </p>
                <p style="margin:0 0 8px 0;font-size:11px;color:#9ca3af;">
                    Or copy this link: {{ $url }}
                </p>

                <p style="margin:20px 0 0 0;font-size:13px;color:#6b7280;">
                    If you did not request a password reset, no further action is required.
                </p>
            </td>
        </tr>
    </table>
</body>

</html>
