<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Domain\Order\Models\Order;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Order $order)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order Created Successfully')
            ->view('emails.orders.created', [
                'order' => $this->order,
            ]);
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'user_id' => $notifiable->id,
            'client_name' => $this->order->user->name,
            'total_amount' => $this->order->total_amount,
            'status' => $this->order->status,
            'payment_method' => $this->order->transaction->gateway,
            'items_count' => $this->order->items->count(),
            'paid_at' => $this->order->paid_at,

        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_uuid' => $this->order->uuid,
            'customer_name' => $this->order->user->name,
            'total_amount' => $this->order->total_amount,
        ];
    }
}
