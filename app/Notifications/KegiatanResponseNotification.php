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
    protected ?string $alasan;

    public function __construct(Kegiatan $kegiatan, User $peserta, string $status, ?string $alasan = null)
    {
        $this->kegiatan = $kegiatan;
        $this->peserta = $peserta;
        $this->status = $status;
        $this->alasan = $alasan;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Explicitly without WhatsApp
    }

    public function toArray(object $notifiable): array
    {
        $statusText = $this->status === 'bersedia' ? 'Bersedia' : 'Tidak Bersedia';
        $message = "{$this->peserta->name} telah menanggapi kegiatan '{$this->kegiatan->judul}' dengan status: {$statusText}";
        if ($this->status === 'tidak_bersedia' && $this->alasan) {
            $message .= " (Alasan: {$this->alasan})";
        }

        return [
            'kegiatan_id' => $this->kegiatan->id,
            'peserta_id' => $this->peserta->id,
            'status' => $this->status,
            'alasan' => $this->alasan,
            'message' => $message,
        ];
    }
}
