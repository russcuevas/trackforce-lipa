<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TrackForce Lipa OTP</title>
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
                <p style="margin:0 0 12px 0;font-size:15px;">Your report has been received. Please verify using this OTP
                    code:</p>
                <p style="margin:0 0 16px 0;font-size:30px;letter-spacing:6px;font-weight:700;color:#CE1126;">
                    {{ $otp }}</p>
                <p style="margin:0 0 8px 0;font-size:14px;">Report Number: <strong>{{ $reportNumber }}</strong></p>
                <p style="margin:0 0 8px 0;font-size:13px;color:#4b5563;">This code expires when your report is
                    verified.</p>

                <p style="margin:20px 0 8px 0;font-size:13px;color:#374151;">
                    You can also verify directly by clicking the button below:
                </p>
                <a href="{{ route('report.verify.page', ['report_number' => $reportNumber]) }}"
                    style="display:inline-block;background:#CE1126;color:#ffffff;text-decoration:none;font-weight:700;font-size:13px;padding:11px 24px;border-radius:8px;letter-spacing:0.5px;">
                    VERIFY MY REPORT
                </a>
                <p style="margin:6px 0 0 0;font-size:11px;color:#9ca3af;">
                    Or copy this link: {{ route('report.verify.page', ['report_number' => $reportNumber]) }}
                </p>

                <p style="margin:20px 0 0 0;font-size:13px;color:#6b7280;">If you did not submit a report, please ignore
                    this email.</p>
            </td>
        </tr>
    </table>
</body>

</html>
