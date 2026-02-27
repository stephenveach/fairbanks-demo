<?php

namespace App\Services;

use RuntimeException;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

class StripeCheckoutService
{
    /**
     * @param  array<int, array{id: string, name: string, price: float, quantity: int}>  $items
     */
    public function createSession(array $items): Session
    {
        return $this->stripeClient()->checkout->sessions->create([
            'mode' => 'payment',
            'line_items' => array_map(function (array $item): array {
                return [
                    'price_data' => [
                        'currency' => config('services.stripe.currency', 'usd'),
                        'unit_amount' => (int) round($item['price'] * 100),
                        'product_data' => [
                            'name' => $item['name'],
                        ],
                    ],
                    'quantity' => $item['quantity'],
                ];
            }, $items),
            'success_url' => route('checkout.complete').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.show'),
        ]);
    }

    public function retrieveSession(string $sessionId): Session
    {
        return $this->stripeClient()->checkout->sessions->retrieve($sessionId);
    }

    private function stripeClient(): StripeClient
    {
        $secret = (string) config('services.stripe.secret', '');

        if ($secret === '') {
            throw new RuntimeException('Stripe secret key is not configured.');
        }

        return new StripeClient($secret);
    }
}
