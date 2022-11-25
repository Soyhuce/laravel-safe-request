<?php declare(strict_types=1);

namespace Soyhuce\LaravelSafeRequest;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelSafeRequestServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-safe-request');
    }
}
