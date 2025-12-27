<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OMNIC E-commerce - {{ config('filament-ecommerce.enable_pricing') ? 'Payment Success' : 'Quote Request Success' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="bg-white p-10 rounded-lg shadow-md text-center">
            @if(config('filament-ecommerce.enable_pricing'))
                <h1 class="text-4xl font-bold text-green-500 mb-4">Payment Successful!</h1>
                <p class="text-gray-700">Thank you for your order. Your payment has been processed successfully.</p>
            @else
                <h1 class="text-4xl font-bold text-green-500 mb-4">Quote Requested Successfully!</h1>
                <p class="text-gray-700">Thank you for your request. We have received your quote request and will contact you shortly.</p>
            @endif
            <a href="/" class="mt-8 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Continue Shopping
            </a>
        </div>
    </div>
</body>
</html>
