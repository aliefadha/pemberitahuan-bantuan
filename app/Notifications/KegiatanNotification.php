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
        $url = route('kegiatan.show', $this->kegiatan);
        $replyInstructions = "\n\nBalas langsung pesan ini menggunakan fitur Reply/Balas dengan:\n- bersedia\n- tidak bersedia <alasan>\n\nContoh: tidak bersedia karena sakit";
        $message = match ($this->type) {
            'created' => "📢 *Kegiatan Baru*\n\nJudul: {$this->kegiatan->judul}{$jorongStr}\n\nTanggal: {$this->kegiatan->tanggal->format('d/m/Y H:i')}\n\nDeskripsi: {$this->kegiatan->deskripsi}\n\nCek kegiatan pada sistem:\n{$url}{$replyInstructions}",
            'updated' => "📝 *Kegiatan Diupdate*\n\nJudul: {$this->kegiatan->judul}{$jorongStr}\n\nTanggal: {$this->kegiatan->tanggal->format('d/m/Y H:i')}\n\nCek kegiatan pada sistem:\n{$url}{$replyInstructions}",
            'deleted' => "🗑️ *Kegiatan Dihapus*\n\nKegiatan '{$this->kegiatan->judul}' telah dibatalkan.",
            default => "Ada perubahan pada kegiatan: {$this->kegiatan->judul}\n\nCek kegiatan pada sistem:\n{$url}",
        };

        return [
            'phone' => $notifiable->whatsapp_number,
            'message' => $message,
            'kegiatan_id' => $this->kegiatan->id,
            'track_response' => in_array($this->type, ['created', 'updated'], true),
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
