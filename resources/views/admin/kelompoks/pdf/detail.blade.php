<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Kelompok - {{ $kelompok->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Serif', Georgia, serif;
            font-size: 11pt;
            color: #111;
            background: #fff;
        }

        /* ==============================
           KOP SURAT
           ============================== */
        .kop-surat {
            padding: 18px 48px 0 48px;
        }

        .kop-inner {
            padding-bottom: 10px;
            text-align: center;
        }

        .kop-nama {
            font-size: 20pt;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #000;
            line-height: 1.2;
        }

        .kop-line-outer {
            margin: 6px 48px 0 48px;
            border-top: 4px solid #000;
        }

        .kop-line-inner {
            margin: 2px 48px 0 48px;
            border-top: 1.5px solid #000;
        }

        /* ==============================
           DOCUMENT BODY
           ============================== */
        .doc-body {
            padding: 16px 48px 40px 48px;
        }

        /* Nomor & Perihal */
        .doc-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .doc-meta {
            font-size: 10pt;
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }

        .doc-meta table {
            border-collapse: collapse;
            width: auto;
        }

        .doc-meta td {
            padding: 1px 4px;
            border: none;
            vertical-align: top;
        }

        .doc-meta td:first-child {
            width: 110px;
            font-weight: bold;
        }

        .doc-meta td:nth-child(2) {
            width: 12px;
            text-align: center;
        }

        .doc-tanggal {
            font-size: 10pt;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            text-align: right;
        }

        /* Judul Surat */
        .doc-title-block {
            text-align: center;
            margin: 18px 0 16px 0;
        }

        .doc-title-block h2 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-decoration: underline;
            color: #111;
        }

        /* Info Kelompok Box */
        .info-box {
            border: 1px solid #aaa;
            margin-bottom: 16px;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
        }

        .info-box-title {
            background: #000;
            color: #fff;
            padding: 5px 12px;
            font-weight: bold;
            font-size: 9.5pt;
            letter-spacing: 0.5px;
        }

        .info-box-body {
            padding: 6px 0;
        }

        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-box td {
            padding: 3px 12px;
            vertical-align: top;
            border: none;
        }

        .info-box td:first-child {
            font-weight: bold;
            width: 120px;
            color: #000;
        }

        .info-box td:nth-child(2) {
            width: 12px;
        }

        /* Main table */
        table.main-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9.5pt;
            margin-top: 8px;
        }

        table.main-table thead tr {
            background: #000;
            color: #fff;
        }

        table.main-table thead th {
            padding: 7px 10px;
            text-align: center;
            font-size: 9pt;
            font-weight: bold;
            border: 1px solid #000;
        }

        table.main-table tbody td {
            padding: 6px 10px;
            border: 1px solid #999;
            vertical-align: middle;
        }

        table.main-table tbody tr:nth-child(even) {
            background: #eee;
        }

        .td-no { text-align: center; width: 30px; }
        .td-role { text-align: center; width: 90px; }
        .td-wa { width: 130px; }
    </style>
</head>
<body>

    {{-- ===== KOP SURAT ===== --}}
    <div class="kop-surat">
        <div class="kop-inner">
            <div class="kop-nama">Sistem Pemberitahuan Bantuan</div>
        </div>
    </div>

    <div class="kop-line-outer"></div>
    <div class="kop-line-inner"></div>

    {{-- ===== BODY SURAT ===== --}}
    <div class="doc-body">

        <div class="doc-header-row">
            <div class="doc-meta">
                <table>
                    <tr>
                        <td>Nomor</td>
                        <td>:</td>
                        <td>{{ now()->format('Y') }}/KLMPK/{{ $kelompok->id }}/{{ now()->format('m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td>1 (satu) berkas</td>
                    </tr>
                    <tr>
                        <td>Hal</td>
                        <td>:</td>
                        <td><strong>Laporan Detail Kelompok</strong></td>
                    </tr>
                </table>
            </div>
            <div class="doc-tanggal">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>

        {{-- Judul Dokumen --}}
        <div class="doc-title-block">
            <h2>Laporan Detail Kelompok</h2>
        </div>

        {{-- Info Kelompok --}}
        <div class="info-box">
            <div class="info-box-title">&#128101; Informasi Kelompok</div>
            <div class="info-box-body">
                <table>
                    <tr>
                        <td>Nama Kelompok</td>
                        <td>:</td>
                        <td><strong>{{ $kelompok->name }}</strong></td>
                    </tr>
                    <tr>
                        <td>Jorong</td>
                        <td>:</td>
                        <td>{{ $kelompok->jorong_label }}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Anggota</td>
                        <td>:</td>
                        <td>{{ $kelompok->users->count() }} Orang</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Tabel Anggota --}}
        <table class="main-table">
            <thead>
                <tr>
                    <th style="width:32px;">No.</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th class="td-wa">Nomor WhatsApp</th>
                    <th class="td-role">Role</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelompok->users as $j => $peserta)
                <tr>
                    <td class="td-no">{{ $j + 1 }}.</td>
                    <td><strong>{{ $peserta->name }}</strong></td>
                    <td>{{ $peserta->email }}</td>
                    <td>{{ $peserta->no_telepon ?? '-' }}</td>
                    <td class="td-role" style="text-transform: capitalize;">{{ $peserta->role }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:20px; color:#555; font-style:italic;">
                        Belum ada anggota di kelompok ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>
</html>
