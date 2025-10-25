<?php

namespace App\Providers;

use Domain\User\Models\User;
use Illuminate\Support\Facades\Gate;
use Domain\Payment\Enums\GatewayEnum;
use Illuminate\Support\ServiceProvider;
use Domain\Payment\Models\CodPaymentTransaction;
use Domain\Payment\Models\StripePaymentTransaction;
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
        Relation::MorphMap([
            GatewayEnum::COD => CodPaymentTransaction::class,
            GatewayEnum::STRIPE => StripePaymentTransaction::class,
            'user' => User::class,
        ]);

        Gate::define('create_order', fn(User $user) => $user->hasPermissionTo('create_order'));
    }
}
