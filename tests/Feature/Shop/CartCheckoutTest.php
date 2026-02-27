<?php

namespace Tests\Feature\Shop;

use Database\Seeders\ProductCollectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_adds_a_product_to_the_cart(): void
    {
        $this->seed(ProductCollectionSeeder::class);

        $response = $this->post(route('cart.items.store'), [
            'product_slug' => 'product-001',
            'quantity' => 2,
        ]);

        $response->assertRedirect();
        $this->assertSame(2, session('shopping_cart.product-001.quantity'));
    }

    #[Test]
    public function checkout_page_redirects_when_cart_is_empty(): void
    {
        $response = $this->get(route('checkout.show'));

        $response->assertRedirect(route('cart.show'));
    }

    #[Test]
    public function checkout_start_requires_stripe_secret_configuration(): void
    {
        config()->set('services.stripe.secret', null);
        $this->seed(ProductCollectionSeeder::class);

        $this->post(route('cart.items.store'), [
            'product_slug' => 'product-001',
            'quantity' => 1,
        ]);

        $response = $this->post(route('checkout.start'));

        $response->assertRedirect(route('checkout.show'));
        $response->assertSessionHasErrors('checkout');
    }
}
