<x-filament-panels::page
    @class([
        'fi-resource-view-record-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
        'fi-resource-record-' . $record->getKey(),
    ])
>
    @php
        $relationManagers = $this->getRelationManagers();
        $hasCombinedRelationManagerTabsWithContent = $this->hasCombinedRelationManagerTabsWithContent();
    @endphp

    <x-filament::section>
        <div class="flex justify-between xl:gap-60 lg:gap-48 md:gap-16 sm:gap-8 sm:flex-row flex-col gap-4">
            <div class="w-full">
                <div class=" my-4">
                    <img src="{{$this->getRecord()->company?->getFirstMediaUrl('logo')}}"
                         alt="{{$this->getRecord()->company?->name}}" class="h-16">
                </div>
                <div class="flex flex-col">
                    <div>
                        {{trans('filament-ecommerce::messages.orders.print.from')}}
                    </div>
                    <div class="text-lg font-bold mt-2">
                        {{$this->getRecord()->company?->name}}
                    </div>
                    <div class="text-sm">
                        {{$this->getRecord()->company?->ceo}}
                    </div>
                    <div class="text-sm">
                        {{$this->getRecord()->company?->address}}
                    </div>
                    <div class="text-sm">
                        {{$this->getRecord()->company?->zip}} {{$this->getRecord()->company?->city}}
                    </div>
                    <div class="text-sm">
                        {{$this->getRecord()->company?->country?->name}}
                    </div>
                </div>
                <div class="mt-4">
                    <div>
                        {{trans('filament-ecommerce::messages.orders.print.to')}}
                    </div>
                    <div class="mt-4">
                        <div class="text-lg font-bold mt-2">
                            {{$this->getRecord()->account?->name}}
                        </div>
                        <div class="text-sm">
                            {{$this->getRecord()->account?->email}}
                        </div>
                        <div class="text-sm">
                            {{$this->getRecord()->account?->phone}}
                        </div>
                        <div class="text-sm">
                            {{$this->getRecord()->address}}
                        </div>
                        @if(isset($this->getRecord()->country->name, $this->getRecord()->city->name, $this->getRecord()->area->name))
                            <div class="text-sm">
                                {{$this->getRecord()->country?->name}} , {{$this->getRecord()->city?->name}}
                                , {{$this->getRecord()->area?->name}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-4 w-full">
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{trans('filament-ecommerce::messages.orders.print.order')}}
                    </div>
                    <div>
                        {{$this->getRecord()->uuid}}
                    </div>
                </div>
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{trans('filament-ecommerce::messages.orders.print.issue_date')}}
                    </div>
                    <div>
                        {{$this->getRecord()->created_at->toDateString()}}
                    </div>
                </div>
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{trans('filament-ecommerce::messages.orders.print.due_date')}}
                    </div>
                    <div>
                        {{$this->getRecord()->created_at->toDateString()}}
                    </div>
                </div>
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{trans('filament-ecommerce::messages.orders.print.status')}}
                    </div>
                    <div>
                        {{str($this->getRecord()->status)->upper()}}
                    </div>
                </div>
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{trans('filament-ecommerce::messages.orders.print.source')}}
                    </div>
                    <div class="font-bold text-primary-500">
                        {{str($this->getRecord()->source)->upper()}}
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                <tr class="border-b dark:border-gray-700 font-bold">
                    <th class="py-4 pr-4">{{trans('filament-ecommerce::messages.orders.print.item')}}</th>
                    @if(config('filament-ecommerce.enable_pricing'))
                        <th class="py-4 px-4">{{trans('filament-ecommerce::messages.orders.print.price')}}</th>
                        <th class="py-4 px-4">{{trans('filament-ecommerce::messages.orders.print.discount')}}</th>
                        <th class="py-4 px-4">{{trans('filament-ecommerce::messages.orders.print.vat')}}</th>
                    @endif
                    <th class="py-4 px-4 text-center">{{trans('filament-ecommerce::messages.orders.print.qty')}}</th>
                    @if(config('filament-ecommerce.enable_pricing'))
                        <th class="py-4 pl-4 text-right">{{trans('filament-ecommerce::messages.orders.print.total')}}</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach($this->getRecord()->ordersItems as $item)
                    <tr class="border-b dark:border-gray-700">
                        <td class="py-4 pr-4">
                            {{ $item->product?->name }}
                        </td>
                        @if(config('filament-ecommerce.enable_pricing'))
                            <td class="py-4 px-4">{!! dollar($item->price) !!}</td>
                            <td class="py-4 px-4">{!! dollar($item->discount) !!}</td>
                            <td class="py-4 px-4">{!! dollar($item->tax) !!}</td>
                        @endif
                        <td class="py-4 px-4 text-center">{{$item->qty}}</td>
                        @if(config('filament-ecommerce.enable_pricing'))
                            <td class="py-4 pl-4 text-right">{!! dollar($item->total) !!}</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @if(config('filament-ecommerce.enable_pricing'))
            <div class="flex flex-col gap-4 mt-4">
                <div class="flex justify-between gap-4 py-4 border-b dark:border-gray-700">
                    <div class="font-bold">
                        {{trans('filament-ecommerce::messages.orders.print.sub_total')}}
                    </div>
                    <div>
                        {!! dollar(($this->getRecord()->total + $this->getRecord()->discount) - ($this->getRecord()->vat + $this->getRecord()->shipping)) !!}
                    </div>
                </div>
                @if($this->getRecord()->vat)
                    <div class="flex justify-between gap-4 py-4 border-b dark:border-gray-700 text-success-500">
                        <div class="font-bold">
                            {{trans('filament-ecommerce::messages.orders.print.vat')}}
                        </div>
                        <div>
                            {!! dollar($this->getRecord()->vat ) !!}
                        </div>
                    </div>
                @endif
                @if($this->getRecord()->shipping)
                    <div class="flex justify-between gap-4 py-4 border-b dark:border-gray-700 text-success-500">
                        <div class="font-bold">
                            {{trans('filament-ecommerce::messages.orders.print.shipping')}}
                        </div>
                        <div>
                            {!! dollar($this->getRecord()->shipping ) !!}
                        </div>
                    </div>
                @endif
                @if($this->getRecord()->coupon)
                    <div class="flex justify-between gap-4 py-4 border-b dark:border-gray-700 text-danger-500">
                        <div class="font-bold">
                            {{trans('filament-ecommerce::messages.orders.print.coupon')}}
                            [{{ $this->getRecord()->coupon->code }}]
                        </div>
                        <div>
                            {!! dollar($this->getRecord()->coupon->discount($this->getRecord()->total) ) !!}
                        </div>
                    </div>
                @endif
                @if($this->getRecord()->discount)
                    <div class="flex justify-between gap-4 py-4 border-b dark:border-gray-700 text-danger-500">
                        <div class="font-bold">
                            {{trans('filament-ecommerce::messages.orders.print.discount')}}
                        </div>
                        <div>
                            @if($this->getRecord()->coupon)
                                {!! dollar($this->getRecord()->discount - $this->getRecord()->coupon->discount($this->getRecord()->total)) !!}
                            @else
                                {!! dollar($this->getRecord()->discount ) !!}
                            @endif
                        </div>
                    </div>
                @endif
                <div class="flex justify-between gap-4 py-4 text-primary-500">
                    <div class="font-bold">
                        {{trans('filament-ecommerce::messages.orders.print.total')}}
                    </div>
                    <div>
                        {!! dollar($this->getRecord()->total) !!}
                    </div>
                </div>
            </div>
        @endif
        @if($this->getRecord()->notes)
            <div class="flex flex-col gap-4 mt-4 py-4 text-gray-800">
                <div class="font-bold">
                    {{trans('filament-ecommerce::messages.orders.print.notes')}}
                </div>
                <div>
                    {{ $this->getRecord()->notes }}
                </div>
            </div>
        @endif
    </x-filament::section>

    @if (count($relationManagers))
        <x-filament-panels::resources.relation-managers
            :active-locale="isset($activeLocale) ? $activeLocale : null"
            :active-manager="$this->activeRelationManager ?? ($hasCombinedRelationManagerTabsWithContent ? null : array_key_first($relationManagers))"
            :managers="$relationManagers"
            :owner-record="$record"
            :page-class="static::class"
        >
            @if ($hasCombinedRelationManagerTabsWithContent)
                <x-slot name="content">
                    @if ($this->hasInfolist())
                        {{ $this->infolist }}
                    @else
                        {{ $this->form }}
                    @endif
                </x-slot>
            @endif
        </x-filament-panels::resources.relation-managers>
    @endif
</x-filament-panels::page>
