<?php

namespace Arseno25\ExcelImport;

use Filament\Contracts\Plugin;
use Filament\Panel;

class ExcelImportPlugin implements Plugin
{

    public function getId(): string
    {
        return 'excel-import';
    }

    public function register(Panel $panel): void
    {
        // TODO: Implement register() method.
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }

    public function make(): static
    {
        return app(static::class);
    }

    public static function get(): Plugin
    {
        return filament(app(static::class)->getId());
    }
}