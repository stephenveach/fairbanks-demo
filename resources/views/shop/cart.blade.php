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

        @if (count($items) === 0)
            <section class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                <p class="text-gray-700">Your cart is empty.</p>
                <p class="mt-4"><a href="/products" class="font-medium text-primary hover:text-primary/80">Browse products</a></p>
            </section>
        @else
            <section class="overflow-hidden rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 bg-white text-sm">
                    <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3">Unit Price</th>
                        <th class="px-4 py-3">Quantity</th>
                        <th class="px-4 py-3">Subtotal</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @foreach ($items as $item)
                        <tr>
                            <td class="px-4 py-4 align-top">
                                <p class="font-medium text-gray-900">{{ $item['name'] }}</p>
                                @if (($item['options']['model_number'] ?? '') !== '')
                                    <p class="text-gray-600">Model: {{ $item['options']['model_number'] }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-4 align-top">${{ number_format($item['price'], 2) }}</td>
                            <td class="px-4 py-4 align-top">
                                <form method="POST" action="{{ route('cart.items.update', $item['id']) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" min="1" max="50" value="{{ $item['quantity'] }}" class="w-20 rounded-md border-gray-300 text-sm">
                                    <button type="submit" class="text-primary hover:text-primary/80">Update</button>
                                </form>
                            </td>
                            <td class="px-4 py-4 align-top">${{ number_format($item['subtotal'], 2) }}</td>
                            <td class="px-4 py-4 align-top">
                                <form method="POST" action="{{ route('cart.items.destroy', $item['id']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </section>

            <section class="flex flex-wrap items-center justify-between gap-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div class="text-sm text-gray-700">Items: <span class="font-semibold">{{ $item_count }}</span></div>
                <div class="text-lg font-semibold text-gray-900">Total: ${{ number_format($total, 2) }}</div>
                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-gray-600 hover:text-primary">Clear cart</button>
                    </form>
                    <a href="{{ route('checkout.show') }}" class="inline-flex rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary/90">Proceed to checkout</a>
                </div>
            </section>
        @endif

        @if ($body_html !== '')
            <section class="prose max-w-none">{!! $body_html !!}</section>
        @endif
    </section>
@endsection
