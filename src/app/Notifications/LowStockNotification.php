<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $ingredient;

    public function __construct($ingredient)
    {
        $this->ingredient = $ingredient;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Hello!')
            ->subject('Low Stock Alert')
            ->line('The stock for ' . $this->ingredient->name . ' is low.')
            ->line('Current stock: ' . $this->ingredient->stock_amount);
    }
}
