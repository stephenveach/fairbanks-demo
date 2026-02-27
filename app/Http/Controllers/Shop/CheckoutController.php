<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\StripeCheckoutService;
use App\Support\ShopPageContent;
use Hnooz\LaravelCart\Facades\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(
        protected ShopPageContent $shopPageContent,
        protected StripeCheckoutService $stripeCheckoutService
    ) {}

    public function show(): View|RedirectResponse
    {
        $items = $this->cartItems();

        if (count($items) === 0) {
            return redirect()->route('cart.show')->withErrors([
                'cart' => 'Your cart is empty.',
            ]);
        }

        $page = $this->shopPageContent->findOrFail('checkout');

        return view('shop.checkout', [
            ...$this->shopPageContent->toViewData($page),
            'items' => $items,
            'item_count' => Cart::count(),
            'total' => Cart::total(),
            'stripe_publishable_key' => (string) config('services.stripe.key', ''),
        ]);
    }

    public function start(): RedirectResponse
    {
        if (! config('services.stripe.secret')) {
            return redirect()->route('checkout.show')->withErrors([
                'checkout' => 'Stripe is not configured yet. Please set STRIPE_SECRET.',
            ]);
        }

        $items = $this->cartItems();

        if (count($items) === 0) {
            return redirect()->route('cart.show')->withErrors([
                'cart' => 'Your cart is empty.',
            ]);
        }

        try {
            $session = $this->stripeCheckoutService->createSession($items);
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('checkout.show')->withErrors([
                'checkout' => 'Could not start Stripe Checkout. Please try again.',
            ]);
        }

        return redirect()->away((string) $session->url);
    }

    public function complete(Request $request): View|RedirectResponse
    {
        $page = $this->shopPageContent->findOrFail('checkout-success');
        $checkoutSessionId = (string) $request->query('session_id', '');
        $paid = false;
        $amountPaid = null;

        if ($checkoutSessionId !== '' && config('services.stripe.secret')) {
            try {
                $session = $this->stripeCheckoutService->retrieveSession($checkoutSessionId);
                $paid = $session->payment_status === 'paid';

                if ($paid) {
                    Cart::clear();
                }

                if ($session->amount_total !== null) {
                    $amountPaid = $session->amount_total / 100;
                }
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        return view('shop.checkout-success', [
            ...$this->shopPageContent->toViewData($page),
            'paid' => $paid,
            'amount_paid' => $amountPaid,
        ]);
    }

    public function cancel(): RedirectResponse
    {
        return redirect()->route('checkout.show')->withErrors([
            'checkout' => 'Checkout was canceled. Your cart is still available.',
        ]);
    }

    /**
     * @return array<int, array{id: string, name: string, price: float, quantity: int}>
     */
    private function cartItems(): array
    {
        return collect(Cart::all())
            ->values()
            ->map(fn (array $item): array => [
                'id' => (string) $item['id'],
                'name' => (string) $item['name'],
                'price' => (float) $item['price'],
                'quantity' => (int) $item['quantity'],
            ])
            ->all();
    }
}
