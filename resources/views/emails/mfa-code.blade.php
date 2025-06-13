<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your MFA Code</title>
</head>
<body style="font-family: sans-serif; padding: 20px; background-color: #f9f9f9;">
    <div style="background: white; padding: 20px; border-radius: 5px; max-width: 600px; margin: auto;">
        <h2>Hello,</h2>
        <p>Here is your <strong>MFA verification code</strong>:</p>
        <div style="font-size: 24px; background: #f0f0f0; padding: 10px 20px; border-radius: 4px; display: inline-block;">
            {{ $code }}
        </div>
        <p>This code will expire in 10 minutes.</p>
        <p style="margin-top: 20px;">Thank you,<br>The {{ config('app.name') }} Team</p>
    </div>
</body>
</html>
