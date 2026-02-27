@extends('shop.layout')

@section('content')
    <section class="grid gap-8">
        <header class="grid gap-2">
            <h1 class="text-4xl font-semibold tracking-tight">{{ $title }}</h1>
            @if ($hero !== '')
                <p class="max-w-3xl text-gray-700">{{ $hero }}</p>
            @endif
        </header>

        @if ($sales_message_html !== '')
            <section class="rounded-lg border border-blue-200 bg-blue-50 p-4 prose max-w-none">{!! $sales_message_html !!}</section>
        @endif

        @if ($notification_message_html !== '')
            <section class="rounded-lg border border-amber-200 bg-amber-50 p-4 prose max-w-none">{!! $notification_message_html !!}</section>
        @endif

        @if ($shipping_delay_html !== '')
            <section class="rounded-lg border border-orange-200 bg-orange-50 p-4 prose max-w-none">{!! $shipping_delay_html !!}</section>
        @endif

        <section class="rounded-lg border border-gray-200 bg-white p-4">
            <h2 class="text-xl font-semibold">Order summary</h2>
            <ul class="mt-4 grid gap-2 text-sm text-gray-700">
                @foreach ($items as $item)
                    <li class="flex items-center justify-between gap-4">
                        <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                        <span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <p class="mt-4 text-lg font-semibold">Total: ${{ number_format($total, 2) }}</p>

            <form method="POST" action="{{ route('checkout.start') }}" class="mt-6">
                @csrf
                <button type="submit" class="inline-flex rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary/90">Pay with Stripe</button>
            </form>
        </section>

        @if ($body_html !== '')
            <section class="prose max-w-none">{!! $body_html !!}</section>
        @endif
    </section>
@endsection
