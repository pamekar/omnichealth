<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quote {{ $order->uuid }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .company-info, .customer-info { width: 48%; float: left; }
        .customer-info { float: right; text-align: right; }
        .clearfix::after { content: ""; display: table; clear: both; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border-bottom: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals { margin-top: 20px; float: right; width: 40%; }
        .totals-row { display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #eee; }
        .totals-row span:last-child { float: right; }
        .totals-row span:first-child { float: left; }
        .notes { margin-top: 40px; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="clearfix">
        <div class="company-info">
            @if($order->company && $order->company->getFirstMediaUrl('logo'))
                <img src="{{ $order->company->getFirstMediaUrl('logo') }}" alt="{{ $order->company->name }}" height="50">
            @endif
            <h3>{{ $order->company->name ?? setting('site_name') }}</h3>
            <p>{{ $order->company->address ?? '' }}</p>
            <p>{{ $order->company->zip ?? '' }} {{ $order->company->city ?? '' }}</p>
            <p>{{ $order->company->country->name ?? '' }}</p>
        </div>
        <div class="customer-info">
            <h3>{{ trans('filament-ecommerce::messages.orders.print.to') }}</h3>
            <p><strong>{{ $order->name }}</strong></p>
            <p>{{ $order->email }}</p>
            <p>{{ $order->phone }}</p>
            <p>{{ $order->address }}</p>
        </div>
    </div>

    <div class="clearfix" style="margin-top: 20px; border-top: 2px solid #eee; padding-top: 10px;">
        <div style="float: left; width: 33%;">
            <strong>{{ trans('filament-ecommerce::messages.orders.print.order') }}:</strong> {{ $order->uuid }}
        </div>
        <div style="float: left; width: 33%; text-align: center;">
            <strong>{{ trans('filament-ecommerce::messages.orders.print.issue_date') }}:</strong> {{ $order->created_at->toDateString() }}
        </div>
        <div style="float: right; width: 33%; text-align: right;">
            <strong>{{ trans('filament-ecommerce::messages.orders.print.status') }}:</strong> {{ strtoupper($order->status) }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ trans('filament-ecommerce::messages.orders.print.item') }}</th>
                <th>{{ trans('filament-ecommerce::messages.orders.print.price') }}</th>
                <th>{{ trans('filament-ecommerce::messages.orders.print.discount') }}</th>
                <th>{{ trans('filament-ecommerce::messages.orders.print.vat') }}</th>
                <th class="text-center">{{ trans('filament-ecommerce::messages.orders.print.qty') }}</th>
                <th class="text-right">{{ trans('filament-ecommerce::messages.orders.print.total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->ordersItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ number_format($item->discount, 2) }}</td>
                    <td>{{ number_format($item->tax ?? $item->vat, 2) }}</td>
                    <td class="text-center">{{ $item->qty }}</td>
                    <td class="text-right">{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="clearfix">
        <div class="totals">
            <div class="totals-row clearfix">
                <span>{{ trans('filament-ecommerce::messages.orders.print.sub_total') }}</span>
                <span>{{ number_format(($order->total + $order->discount) - ($order->vat + $order->shipping), 2) }}</span>
            </div>
            @if($order->vat)
                <div class="totals-row clearfix">
                    <span>{{ trans('filament-ecommerce::messages.orders.print.vat') }}</span>
                    <span>{{ number_format($order->vat, 2) }}</span>
                </div>
            @endif
            @if($order->shipping)
                <div class="totals-row clearfix">
                    <span>{{ trans('filament-ecommerce::messages.orders.print.shipping') }}</span>
                    <span>{{ number_format($order->shipping, 2) }}</span>
                </div>
            @endif
            @if($order->discount)
                <div class="totals-row clearfix">
                    <span>{{ trans('filament-ecommerce::messages.orders.print.discount') }}</span>
                    <span>{{ number_format($order->discount, 2) }}</span>
                </div>
            @endif
            <div class="totals-row clearfix" style="font-weight: bold; border-top: 2px solid #ddd;">
                <span>{{ trans('filament-ecommerce::messages.orders.print.total') }}</span>
                <span>{{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    @if($order->notes)
        <div class="notes">
            <strong>{{ trans('filament-ecommerce::messages.orders.print.notes') }}:</strong>
            <p>{{ $order->notes }}</p>
        </div>
    @endif
</body>
</html>
