<!DOCTYPE html>
<html>
<head>
    <title>New Quote Request</title>
</head>
<body>
    <p>Hello Admin,</p>
    <p>A new quote request has been received.</p>
    <p><strong>Order ID:</strong> {{ $order->uuid }}</p>
    <p><strong>Customer:</strong> {{ $order->name }} ({{ $order->email }})</p>
    <p>Please log in to the admin panel to view and process the quote.</p>
</body>
</html>
