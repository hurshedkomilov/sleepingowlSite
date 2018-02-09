<?php

namespace App\Mail;

use App\Model\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $order;

    /**
     * Создать новый экземпляр сообщения.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->subject = $order->type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.orders_shipped');
    }
}
