<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OMNIC E-commerce - Payment Error</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="bg-white p-10 rounded-lg shadow-md text-center">
            <h1 class="text-4xl font-bold text-red-500 mb-4">Payment Failed</h1>
            <p class="text-gray-700">
                @if(session('error'))
                    {{ session('error') }}
                @else
                    There was an error processing your payment. Please try again.
                @endif
            </p>
            <a href="{{ route('checkout.index') }}" class="mt-8 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Try Again
            </a>
        </div>
    </div>
</body>
</html>
