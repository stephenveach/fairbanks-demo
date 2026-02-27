<?php

namespace App\Support;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Statamic\Entries\Entry;
use Statamic\Facades\Entry as EntryFacade;

class ShopPageContent
{
    public function findOrFail(string $slug): Entry
    {
        $entry = EntryFacade::query()
            ->where('collection', 'shop_pages')
            ->where('slug', $slug)
            ->first();

        if (! $entry) {
            throw (new ModelNotFoundException)->setModel(Entry::class, [$slug]);
        }

        return $entry;
    }

    /**
     * @return array{
     *     title: string,
     *     hero: string,
     *     sales_message_html: string,
     *     notification_message_html: string,
     *     shipping_delay_html: string,
     *     body_html: string
     * }
     */
    public function toViewData(Entry $entry): array
    {
        return [
            'title' => (string) $entry->get('title', ''),
            'hero' => (string) $entry->get('hero', ''),
            'sales_message_html' => Str::markdown((string) $entry->get('sales_message', '')),
            'notification_message_html' => Str::markdown((string) $entry->get('notification_message', '')),
            'shipping_delay_html' => Str::markdown((string) $entry->get('shipping_delay_message', '')),
            'body_html' => Str::markdown((string) $entry->get('body', '')),
        ];
    }
}
