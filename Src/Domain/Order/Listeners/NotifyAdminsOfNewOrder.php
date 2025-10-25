<?php

namespace Domain\Order\Listeners;

use Exception;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Log;
use Domain\Order\Events\OrderCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Domain\Order\Notifications\OrderCreatedNotification;

class NotifyAdminsOfNewOrder implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        try {
            $admins = User::role('admin')->get();
            Log::info("NotifyAdminsOfNewOrder: found " . $admins->count() . " admins to notify about order {$event->order->id}");
            Notification::send($admins, new OrderCreatedNotification($event->order));
            Log::info("NotifyAdminsOfNewOrder: notified " . $admins->count() . " admins about order {$event->order->id}");
        } catch (Exception $e) {
            Log::error("NotifyAdminsOfNewOrder: failed to notify admins about order {$event->order->id}: " . $e->getMessage());
        }
    }
}
