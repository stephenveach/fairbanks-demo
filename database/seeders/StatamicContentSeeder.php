<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use RuntimeException;

class StatamicContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if ($this->usesEloquentDriver('sites')) {
            $this->runImport('statamic:eloquent:import-sites');
        }

        if ($this->usesEloquentDriver('blueprints') || $this->usesEloquentDriver('fieldsets')) {
            $this->runImport('statamic:eloquent:import-blueprints', ['--force' => true, '--all-blueprints' => true]);
        }

        if ($this->usesEloquentDriver('collections') || $this->usesEloquentDriver('collection_trees')) {
            $this->runImport('statamic:eloquent:import-collections', ['--force' => true]);
        }

        if ($this->usesEloquentDriver('taxonomies') || $this->usesEloquentDriver('terms')) {
            $this->runImport('statamic:eloquent:import-taxonomies', ['--force' => true]);
        }

        if ($this->usesEloquentDriver('navigations') || $this->usesEloquentDriver('navigation_trees')) {
            $this->runImport('statamic:eloquent:import-navs', ['--force' => true]);
        }

        if ($this->usesEloquentDriver('global_sets') || $this->usesEloquentDriver('global_set_variables')) {
            $this->runImport('statamic:eloquent:import-globals', ['--force' => true]);
        }

        if ($this->usesEloquentDriver('asset_containers') || $this->usesEloquentDriver('assets')) {
            $this->runImport('statamic:eloquent:import-assets', ['--force' => true]);
        }

        if ($this->usesEloquentDriver('forms') || $this->usesEloquentDriver('form_submissions')) {
            $this->runImport('statamic:eloquent:import-forms', ['--force' => true]);
        }

        if ($this->usesEloquentDriver('addon_settings')) {
            $this->runImport('statamic:eloquent:import-addon-settings');
        }

        if ($this->usesEloquentDriver('entries')) {
            $this->runImport('statamic:eloquent:import-entries');
        }

        if ($this->canImportRoles()) {
            $this->runImport('statamic:eloquent:import-roles');
        } else {
            $this->warnSkip('statamic:eloquent:import-roles');
        }

        if ($this->canImportGroups()) {
            $this->runImport('statamic:eloquent:import-groups');
        } else {
            $this->warnSkip('statamic:eloquent:import-groups');
        }

        if ($this->canImportUsers()) {
            $this->runImport('statamic:eloquent:import-users');
        } else {
            $this->warnSkip('statamic:eloquent:import-users');
        }
    }

    /**
     * @param  array<string, bool|string|int>  $options
     */
    private function runImport(string $command, array $options = []): void
    {
        $defaultOptions = [
            '--no-interaction' => true,
        ];

        $code = Artisan::call($command, array_merge($defaultOptions, $options));

        if ($this->command !== null) {
            $this->command->line(Artisan::output());
        }

        if ($code !== 0) {
            throw new RuntimeException("Statamic import command failed: {$command}");
        }
    }

    private function usesEloquentDriver(string $key): bool
    {
        return config("statamic.eloquent-driver.{$key}.driver", 'file') === 'eloquent';
    }

    private function canImportRoles(): bool
    {
        return (bool) config('statamic.users.tables.roles', false);
    }

    private function canImportGroups(): bool
    {
        return (bool) config('statamic.users.tables.groups', false);
    }

    private function canImportUsers(): bool
    {
        if (config('statamic.users.repository') !== 'eloquent') {
            return false;
        }

        $guard = (string) config('statamic.users.guards.cp', 'web');
        $provider = config("auth.guards.{$guard}.provider");
        $model = config("auth.providers.{$provider}.model");

        return is_string($model) && in_array(HasUuids::class, class_uses_recursive($model), true);
    }

    private function warnSkip(string $command): void
    {
        if ($this->command !== null) {
            $this->command->warn("Skipping {$command} due to current Statamic user/auth config.");
        }
    }
}
