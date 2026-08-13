# Pemberitahuan Dosen

Aplikasi Laravel untuk pengelolaan dokumen dan pemberitahuan kepada dosen menggunakan WhatsApp.

## Persyaratan

- PHP >= 8.2
- Composer
- Node.js & npm
- SQLite/MySQL/PostgreSQL database
- WhatsApp Web (untuk notifikasi WhatsApp)

## Instalasi

1. **Clone repository**
```bash
git clone <repository-url>
cd pemberitahuan-dosen
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Copy environment file**
```bash
cp .env.example .env
```

4. **Generate application key**
```bash
php artisan key:generate
```

5. **Configure database** di `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pemberitahuan_dosen
DB_USERNAME=root
DB_PASSWORD=
```

6. **Run migrations**
```bash
php artisan migrate
```

7. **Seed database (buat admin awal)**
```bash
php artisan db:seed --class=AdminUserSeeder
```

8. **Build assets**
```bash
npm run build
```

## Menjalankan Aplikasi

1. **Start Laravel server**
```bash
php artisan serve
```

2. **Start WhatsApp Service** (di terminal terpisah)
```bash
cd whatsapp-service
npm install
node index.js
```

3. **Buka browser**
```
http://localhost:8000
```

## Login Default

- **Admin**: admin@example.com / password
- **Dosen**: dosen@example.com / password

## Fitur

- **Authentication**: Login/Register dengan role (Admin/Dosen)
- **Manajemen Dokumen**: Admin membuat dokumen, Dosen upload submission
- **Notifikasi WhatsApp**: Kirim pengingat ke dosen via WhatsApp Web
- **Dashboard**: Stats dan overview berbeda untuk Admin dan Dosen

## WhatsApp Setup

1. Buka `/admin/whatsapp`
2. Scan QR Code dengan WhatsApp di HP
3. Pastikan HP terhubung ke internet saat mengirim pesan
4. Hanya 1 nomor WhatsApp yang bisa terhubung dalam satu waktu

## Struktur Project

```
app/
├── Http/Controllers/
│   ├── Admin/           # Controller untuk admin
│   └── Auth/            # Controller untuk auth
├── Models/              # Eloquent models
├── Notifications/       # Notification classes
└── Services/           # WhatsApp service

database/
├── migrations/         # Database migrations
└── seeders/           # Database seeders

resources/views/
├── admin/              # View untuk admin
├── auth/               # View untuk auth
├── dokumen/            # View untuk dokumen
└── layouts/           # Layout templates

whatsapp-service/       # Node.js WhatsApp Web service
```

## Lisensi

MIT License
