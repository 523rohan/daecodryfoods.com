<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminOrderPlacedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->view('backend.pages.orders.admin_invoice', [
                'order' => $this->order,
            ])
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(localize('New Order Received') . ' - #' . getSetting('order_code_prefix') . $this->order->orderGroup->order_code);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
