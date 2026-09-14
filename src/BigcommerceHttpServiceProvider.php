<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp;

use RobMellett\BigcommerceHttp\Commands\BigcommerceHttpCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BigcommerceHttpServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('bigcommerce-http')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_bigcommerce_http_table')
            ->hasCommand(BigcommerceHttpCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(BigcommerceHttp::class);
    }
}
