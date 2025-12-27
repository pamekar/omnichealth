<!DOCTYPE html>
<html>
<head>
    <title>New Quote Request</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <p>Hello Admin,</p>
    <p>A new quote request has been received from <strong>{{ $order->name }}</strong>.</p>
    
    <p><strong>Order ID:</strong> {{ $order->uuid }}</p>
    <p><strong>Email:</strong> {{ $order->email }}</p>
    <p><strong>Phone:</strong> {{ $order->phone }}</p>

    <h3>Request Summary</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->ordersItems as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Unknown Product' }}</td>
                    <td>{{ $item->qty }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>
        <a href="{{ url('/admin/orders') }}" class="btn">View Orders in Admin Panel</a>
    </p>
    
    <p>Please log in to process this quote.</p>
</body>
</html>