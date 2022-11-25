<?php declare(strict_types=1);

namespace Soyhuce\LaravelSafeRequest;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelSafeRequestServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-safe-request');
    }

    public function packageBooted(): void
    {
        FormRequest::mixin(new FormRequestMixin());
    }
}
