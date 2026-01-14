<?php

namespace Kalourmade\MathRichEditor;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MathRichEditorServiceProvider extends PackageServiceProvider
{
    public static string $name = 'math-rich-editor';

    public static string $viewNamespace = 'math-rich-editor';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews(static::$viewNamespace)
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        //
    }

    public function packageBooted(): void
    {
        // Register assets with lazy loading
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        // Publish config file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->package->basePath('/../config/math-rich-editor.php') => config_path('math-rich-editor.php'),
            ], "{$this->packageName()}-config");
        }
    }

    protected function getAssetPackageName(): string
    {
        return 'kwame-boateng/math-rich-editor';
    }

    /**
     * @return array<\Filament\Support\Assets\Asset>
     */
    protected function getAssets(): array
    {
        return [
            Css::make('math-rich-editor-styles', __DIR__ . '/../dist/plugin.css')
                ->loadedOnRequest(),
            Js::make('math-rich-editor-scripts', __DIR__ . '/../dist/plugin.js')
                ->loadedOnRequest(),
        ];
    }

    protected function packageName(): string
    {
        return static::$name;
    }
}
