<?php

namespace App\Notifications;

use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class KegiatanResponseNotification extends Notification
{
    use Queueable;

    protected Kegiatan $kegiatan;
    protected User $peserta;
    protected string $status;

    public function __construct(Kegiatan $kegiatan, User $peserta, string $status)
    {
        $this->kegiatan = $kegiatan;
        $this->peserta = $peserta;
        $this->status = $status;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Explicitly without WhatsApp
    }

    public function toArray(object $notifiable): array
    {
        $statusText = $this->status === 'bersedia' ? 'Bersedia' : 'Tidak Bersedia';
        $message = "{$this->peserta->name} telah menanggapi kegiatan '{$this->kegiatan->judul}' dengan status: {$statusText}";

        return [
            'kegiatan_id' => $this->kegiatan->id,
            'peserta_id' => $this->peserta->id,
            'status' => $this->status,
            'message' => $message,
        ];
    }
}
