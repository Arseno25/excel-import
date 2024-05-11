<?php

namespace Arseno25\ExcelImport;

use Arseno25\ExcelImport\Commands\ExcelImportActionCommand;
use Arseno25\ExcelImport\Testing\TestsExcelImportAction;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ExcelImportServiceProvider extends PackageServiceProvider
{
    public static string $name = 'excel-import';

    public static string $viewNamespace = 'excel-import';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasCommands( $this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('arseno25/excel-import');
        });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
           $package->hasConfigFile();
        }

        if (file_exists($package->basePath("/../database/migrations"))) {
            $package->hasMigrations( $this->getMigrations());
        }

        if (file_exists($package->basePath("/../resources/lang"))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath("/../resources/views"))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
        //
    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/excel-import/{$file->getFilename()}"),
                ], 'excel-import-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsExcelImportAction());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'Arseno25/excel-import';
    }

    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-excel-import', __DIR__ . '/../resources/dist/components/filament-excel-import.js'),
            Css::make('excel-import-styles', __DIR__ . '/../resources/dist/excel-import.css'),
            Js::make('excel-import-scripts', __DIR__ . '/../resources/dist/excel-import.js'),
        ];
    }

    protected function getCommands(): array
    {
        return [
            ExcelImportActionCommand::class,
        ];
    }

    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_excel-import_table',
        ];
    }

}