<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DeliverymanAssignNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return getSetting('delivery_boy_send_mail') ? ['mail','database'] : ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->view('backend.pages.orders.invoice', [
                'order' => $this->order,
                'font_family' => env('INVOICE_FONT') ?: 'THSarabunNew.ttf',
            ])
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(localize('Order Placed') . ' - ' . env('APP_NAME'));
    }

    public function toArray($notifiable)
    {
        return [
            'message'=> localize('You have been assigned to a new order'),
            'url' => route('deliveryman.assigned')
        ];
    }
}
