<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Domain\Order\Events\OrderCreated;
use Domain\Payment\Enums\GatewayEnum;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Domain\Payment\Models\CodPaymentTransaction;
use Domain\Order\Listeners\NotifyAdminsOfNewOrder;
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

        Event::listen(
            OrderCreated::class,
            NotifyAdminsOfNewOrder::class,
        );

        if (app()->environment('local')) {
            DB::listen(function ($query) {
                $sql = $query->sql;
                $bindings = $query->bindings;

                // Replace ? with binding values for readability
                foreach ($bindings as $binding) {
                    $binding = is_numeric($binding) ? $binding : "'" . addslashes($binding) . "'";
                    $sql = Str::replaceFirst('?', $binding, $sql);
                }

                $formatted = sprintf(
                    "\n[%s]\nSQL: %s\nTime: %s ms\n",
                    now()->format('Y-m-d H:i:s'),
                    $sql,
                    $query->time
                );

                Log::channel('sql')->info($formatted);
            });
        }

        $this->loadTranslationsFrom(__DIR__ . '/../../Src/Application/Order/Lang', 'order');
    }
}
