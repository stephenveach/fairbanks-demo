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
        $this->runImport('statamic:eloquent:import-sites');
        $this->runImport('statamic:eloquent:import-blueprints', ['--force' => true, '--all-blueprints' => true]);
        $this->runImport('statamic:eloquent:import-collections', ['--force' => true]);
        $this->runImport('statamic:eloquent:import-taxonomies', ['--force' => true]);
        $this->runImport('statamic:eloquent:import-navs', ['--force' => true]);
        $this->runImport('statamic:eloquent:import-globals', ['--force' => true]);
        $this->runImport('statamic:eloquent:import-assets', ['--force' => true]);
        $this->runImport('statamic:eloquent:import-forms', ['--force' => true]);
        $this->runImport('statamic:eloquent:import-addon-settings');
        $this->runImport('statamic:eloquent:import-entries');

        if ($this->canImportRoles()) {
            $this->runImport('statamic:eloquent:import-roles');
        }

        if ($this->canImportGroups()) {
            $this->runImport('statamic:eloquent:import-groups');
        }

        if ($this->canImportUsers()) {
            $this->runImport('statamic:eloquent:import-users');
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
}
