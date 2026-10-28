<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    public function __construct(public Product $product) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if (config('mail.default') && config('mail.default') !== 'null' && config('app.enable_stock_alert_email', false)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->error()
            ->subject("⚠️ Peringatan Stok Kritis: {$this->product->name}")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Stok produk **{$this->product->name}** (SKU: `{$this->product->sku}`) telah mencapai batas minimum.")
            ->line("Sisa stok: **{$this->product->stock} unit** (Batas minimum: **{$this->product->min_stock} unit**).")
            ->action('Lihat Daftar Stok Kritis', route('products.low-stock'))
            ->line('Segera lakukan restock atau purchase order ke supplier.');
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'sku' => $this->product->sku,
            'stock' => (int) $this->product->stock,
            'min_stock' => (int) $this->product->min_stock,
            'title' => 'Stok Kritis: '.$this->product->name,
            'message' => "Stok produk {$this->product->name} tersisa {$this->product->stock} unit (Batas min: {$this->product->min_stock}).",
            'url' => route('products.low-stock'),
            'type' => 'low_stock',
        ];
    }
}
