<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Statamic\Facades\Collection;
use Statamic\Facades\Entry;

class ProductCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collection = Collection::findByHandle('products');

        if (! $collection) {
            $this->command?->warn('Products collection not found. Skipping ProductCollectionSeeder.');

            return;
        }

        $productFamilies = [
            'Pumps',
            'Compressors',
            'Conveyors',
            'Hydraulic Power Units',
            'Filtration Systems',
            'Mixing Systems',
            'Heat Exchangers',
        ];

        $applicationProfiles = [
            'Mining and aggregate processing',
            'Food and beverage production',
            'Wastewater treatment plants',
            'Oil and gas upstream facilities',
            'General manufacturing lines',
            'Bulk material handling',
            'Chemical process operations',
        ];

        $features = [
            'Corrosion-resistant housing',
            'High-efficiency drive system',
            'Low-noise operation',
            'Quick-access maintenance points',
            'Integrated thermal protection',
            'Heavy-duty bearing design',
            'Extended duty cycle support',
            'Remote monitoring ready',
            'Sealed control enclosure',
            'Vibration-dampened frame',
        ];

        for ($index = 1; $index <= 20; $index++) {
            $family = $productFamilies[array_rand($productFamilies)];
            $application = $applicationProfiles[array_rand($applicationProfiles)];
            $status = $this->statusForIndex($index);
            $slug = 'product-'.str_pad((string) $index, 3, '0', STR_PAD_LEFT);
            $modelNumber = 'FBX-'.str_pad((string) (1000 + $index), 4, '0', STR_PAD_LEFT);
            $title = "{$family} {$modelNumber}";

            $entry = Entry::query()
                ->where('collection', 'products')
                ->where('slug', $slug)
                ->first() ?? Entry::make();

            $entry
                ->collection($collection)
                ->slug($slug)
                ->published(true)
                ->data([
                    'title' => $title,
                    'model_number' => $modelNumber,
                    'price' => 1200 + ($index * 95),
                    'product_family' => $family,
                    'status' => $status,
                    'featured_product' => $index % 5 === 0,
                    'short_description' => "Industrial {$family} engineered for {$application}.",
                    'operating_conditions' => 'Designed for continuous industrial duty in harsh environments.',
                    'key_features' => $this->featureRows($features),
                    'technical_specifications' => $this->specificationRows($index),
                    'call_to_action_label' => 'Request a Quote',
                    'call_to_action_url' => "/contact?product={$slug}",
                ])
                ->save();
        }
    }

    /**
     * @return array<int, array{feature: string}>
     */
    private function featureRows(array $features): array
    {
        shuffle($features);

        return collect(array_slice($features, 0, 4))
            ->map(fn (string $feature): array => ['feature' => $feature])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{specification: string, value: string, unit: string}>
     */
    private function specificationRows(int $index): array
    {
        return [
            [
                'specification' => 'Flow Rate',
                'value' => (string) (80 + ($index * 3)),
                'unit' => 'm3/h',
            ],
            [
                'specification' => 'Operating Pressure',
                'value' => (string) (12 + ($index % 6)),
                'unit' => 'bar',
            ],
            [
                'specification' => 'Power Draw',
                'value' => (string) (7 + ($index % 9)),
                'unit' => 'kW',
            ],
            [
                'specification' => 'Weight',
                'value' => (string) (180 + ($index * 14)),
                'unit' => 'kg',
            ],
        ];
    }

    private function statusForIndex(int $index): string
    {
        if ($index % 9 === 0) {
            return 'discontinued';
        }

        if ($index % 4 === 0) {
            return 'coming_soon';
        }

        return 'active';
    }
}
