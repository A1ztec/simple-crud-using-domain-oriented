<?php

namespace App\Providers;

use Domain\Payment\Enums\GatewayEnum;
use Illuminate\Support\ServiceProvider;
use Domain\Payment\Models\CodPaymentTransaction;
use Domain\Payment\Models\StripePaymentTransaction;
use Illuminate\Auth\Access\Gate;
use Illuminate\Database\Eloquent\Relations\Relation;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            GatewayEnum::COD => CodPaymentTransaction::class,
            GatewayEnum::STRIPE => StripePaymentTransaction::class,
        ]);
    }
}
