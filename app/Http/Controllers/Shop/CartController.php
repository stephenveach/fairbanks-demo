<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\AddToCartRequest;
use App\Http\Requests\Shop\UpdateCartItemRequest;
use App\Support\ShopPageContent;
use Hnooz\LaravelCart\Facades\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Statamic\Facades\Entry;

class CartController extends Controller
{
    public function __construct(protected ShopPageContent $shopPageContent) {}

    public function show(): View
    {
        $page = $this->shopPageContent->findOrFail('cart');

        return view('shop.cart', [
            ...$this->shopPageContent->toViewData($page),
            'items' => $this->mappedItems(),
            'item_count' => Cart::count(),
            'total' => Cart::total(),
        ]);
    }

    public function store(AddToCartRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $product = Entry::query()
            ->where('collection', 'products')
            ->where('slug', $validated['product_slug'])
            ->first();

        if (! $product || ! $product->published()) {
            return redirect()->back()->withErrors([
                'product_slug' => 'This product could not be found.',
            ]);
        }

        $price = (float) $product->get('price', 0);

        if ($price <= 0) {
            return redirect()->back()->withErrors([
                'product_slug' => 'This product is not purchasable yet.',
            ]);
        }

        Cart::add(
            id: $product->slug(),
            name: (string) $product->get('title'),
            price: $price,
            quantity: (int) ($validated['quantity'] ?? 1),
            options: [
                'model_number' => (string) $product->get('model_number', ''),
                'product_family' => (string) $product->get('product_family', ''),
                'product_url' => (string) $product->url(),
            ],
        );

        return redirect()
            ->back()
            ->with('status', 'Product added to cart.');
    }

    public function update(UpdateCartItemRequest $request, string $itemId): RedirectResponse
    {
        $currentItem = collect(Cart::all())->firstWhere('id', $itemId);

        if (! $currentItem) {
            return redirect()->route('cart.show')->withErrors([
                'cart' => 'Cart item could not be found.',
            ]);
        }

        $currentQuantity = (int) $currentItem['quantity'];
        $newQuantity = (int) $request->validated('quantity');

        if ($newQuantity > $currentQuantity) {
            Cart::increase($itemId, $newQuantity - $currentQuantity);
        }

        if ($newQuantity < $currentQuantity) {
            Cart::decrease($itemId, $currentQuantity - $newQuantity);
        }

        return redirect()->route('cart.show')->with('status', 'Cart updated.');
    }

    public function destroy(string $itemId): RedirectResponse
    {
        Cart::remove($itemId);

        return redirect()->route('cart.show')->with('status', 'Item removed from cart.');
    }

    public function clear(): RedirectResponse
    {
        Cart::clear();

        return redirect()->route('cart.show')->with('status', 'Cart cleared.');
    }

    /**
     * @return array<int, array{
     *     id: string,
     *     name: string,
     *     price: float,
     *     quantity: int,
     *     subtotal: float,
     *     options: array<string, mixed>
     * }>
     */
    private function mappedItems(): array
    {
        return collect(Cart::all())
            ->values()
            ->map(function (array $item): array {
                $price = (float) $item['price'];
                $quantity = (int) $item['quantity'];

                return [
                    'id' => (string) $item['id'],
                    'name' => (string) $item['name'],
                    'price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $price * $quantity,
                    'options' => $item['options'] ?? [],
                ];
            })
            ->all();
    }
}
