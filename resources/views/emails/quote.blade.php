<!DOCTYPE html>
<html>
<head>
    <title>Your Quote</title>
</head>
<body>
    <p>Dear {{ $order->name }},</p>
    <p>Please find attached the quote you requested.</p>
    <p>If you have any questions, please contact us.</p>
    <p>Best regards,<br>{{ setting('site_name') }}</p>
</body>
</html>
