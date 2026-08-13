<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessNotification extends Notification
{
    use Queueable;

    public $pembayaran;
    public $casis;

    /**
     * Create a new notification instance.
     */
    public function __construct($pembayaran, $casis)
    {
        $this->pembayaran = $pembayaran;
        $this->casis = $casis;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pembayaran Daftar Ulang Lunas',
            'message' => 'Siswa ' . $this->casis->nama_lengkap . ' (No Pendaftaran: ' . $this->casis->no_pendaftaran . ') telah melunasi biaya daftar ulang.',
            'url' => route('admin.casis.show', $this->casis->id),
        ];
    }
}
