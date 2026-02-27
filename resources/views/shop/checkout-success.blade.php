@extends('shop.layout')

@section('content')
    <section class="grid gap-8">
        <header class="grid gap-2">
            <h1 class="text-4xl font-semibold tracking-tight">{{ $title }}</h1>
            @if ($hero !== '')
                <p class="max-w-3xl text-gray-700">{{ $hero }}</p>
            @endif
        </header>

        @if ($paid)
            <section class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
                <p class="font-semibold">Payment confirmed.</p>
                @if ($amount_paid !== null)
                    <p class="mt-1">Amount paid: ${{ number_format($amount_paid, 2) }}</p>
                @endif
            </section>
        @else
            <section class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-amber-900">
                <p class="font-semibold">We could not verify payment status from this page load.</p>
                <p class="mt-1">If you completed payment, contact support with your Stripe receipt.</p>
            </section>
        @endif

        @if ($sales_message_html !== '')
            <section class="rounded-lg border border-blue-200 bg-blue-50 p-4 prose max-w-none">{!! $sales_message_html !!}</section>
        @endif

        @if ($notification_message_html !== '')
            <section class="rounded-lg border border-amber-200 bg-amber-50 p-4 prose max-w-none">{!! $notification_message_html !!}</section>
        @endif

        @if ($shipping_delay_html !== '')
            <section class="rounded-lg border border-orange-200 bg-orange-50 p-4 prose max-w-none">{!! $shipping_delay_html !!}</section>
        @endif

        @if ($body_html !== '')
            <section class="prose max-w-none">{!! $body_html !!}</section>
        @endif

        <p><a href="/products" class="font-medium text-primary hover:text-primary/80">Continue shopping</a></p>
    </section>
@endsection
