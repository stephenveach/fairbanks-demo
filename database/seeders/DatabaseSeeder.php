<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Statamic\Facades\Entry;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            StatamicContentSeeder::class,
        ]);

        if (app()->environment('local') && Entry::query()->where('collection', 'products')->count() === 0) {
            $this->call([
                ProductCollectionSeeder::class,
            ]);
        }

        if (app()->environment('local')) {
            User::query()->firstOrCreate(
                ['email' => 'test@example.com'],
                ['name' => 'Test User', 'password' => bcrypt('password')]
            );
        }
    }
}
