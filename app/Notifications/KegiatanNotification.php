<?php

namespace App\Notifications;

use App\Models\Kegiatan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class KegiatanNotification extends Notification
{
    use Queueable;

    protected string $type;

    protected Kegiatan $kegiatan;

    public function __construct(Kegiatan $kegiatan, string $type)
    {
        $this->kegiatan = $kegiatan;
        $this->type = $type;
    }

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->no_telepon) {
            $channels[] = 'whatsapp';
        }

        return $channels;
    }

    public function toWhatsApp(object $notifiable): array
    {
        $jorongStr = $this->kegiatan->jorong_label ? "\nJorong: {$this->kegiatan->jorong_label}" : '';
        $message = match ($this->type) {
            'created' => "📢 *Kegiatan Baru*\n\nJudul: {$this->kegiatan->judul}{$jorongStr}\n\nTanggal: {$this->kegiatan->tanggal->format('d/m/Y H:i')}\n\nDeskripsi: {$this->kegiatan->deskripsi}\n\nCek kegiatan pada sistem.",
            'updated' => "📝 *Kegiatan Diupdate*\n\nJudul: {$this->kegiatan->judul}{$jorongStr}\n\nTanggal: {$this->kegiatan->tanggal->format('d/m/Y H:i')}\n\nCek kegiatan pada sistem.",
            'deleted' => "🗑️ *Kegiatan Dihapus*\n\nKegiatan '{$this->kegiatan->judul}' telah dibatalkan.",
            default => "Ada perubahan pada kegiatan: {$this->kegiatan->judul}",
        };

        return [
            'phone' => $notifiable->whatsapp_number,
            'message' => $message,
        ];
    }

    public function toArray(object $notifiable): array
    {
        $jorongStr = $this->kegiatan->jorong_label ? " ({$this->kegiatan->jorong_label})" : '';
        $message = match ($this->type) {
            'created' => "Admin membuat kegiatan baru: {$this->kegiatan->judul}{$jorongStr}",
            'updated' => "Admin mengupdate kegiatan: {$this->kegiatan->judul}{$jorongStr}",
            'deleted' => "Admin menghapus kegiatan: {$this->kegiatan->judul}",
            default => "Ada perubahan pada kegiatan: {$this->kegiatan->judul}",
        };

        return [
            'kegiatan_id' => $this->kegiatan->id,
            'type' => $this->type,
            'message' => $message,
        ];
    }
}
