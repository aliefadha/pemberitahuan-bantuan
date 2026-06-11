<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Daftar Kelompok</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Serif', Georgia, serif;
            font-size: 11pt;
            color: #111;
            background: #fff;
            padding: 0;
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

        /* Double line separator under kop */
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

        /* Nomor & Perihal block */
        .doc-meta {
            margin-bottom: 18px;
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

        /* Sifat / tanggal di kanan */
        .doc-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .doc-tanggal {
            font-size: 10pt;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            text-align: right;
        }

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

        /* Summary box */
        .summary-box {
            border: 1px solid #bbb;
            border-radius: 2px;
            margin-bottom: 16px;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9.5pt;
        }

        .summary-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-box td {
            padding: 5px 12px;
            border: none;
        }

        .summary-box tr:not(:last-child) td {
            border-bottom: 1px solid #ddd;
        }

        .summary-box .label {
            font-weight: bold;
            color: #000;
            width: 40%;
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
            vertical-align: top;
        }

        table.main-table tbody tr:nth-child(even) {
            background: #eee;
        }

        .td-no {
            text-align: center;
            width: 30px;
        }

        .td-jumlah {
            text-align: center;
            width: 120px;
        }

        .td-jorong {
            text-align: center;
            width: 150px;
        }

        /* Footer note */
        .footer-note {
            margin-top: 20px;
            border-top: 1px solid #999;
            padding-top: 6px;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8pt;
            color: #444;
            text-align: center;
        }
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

        {{-- Nomor, Sifat, Perihal --}}
        <div class="doc-header-row">
            <div class="doc-meta">
                <table>
                    <tr>
                        <td>Nomor</td>
                        <td>:</td>
                        <td>{{ now()->format('Y') }}/KLMPK/{{ str_pad(1, 3, '0', STR_PAD_LEFT) }}/{{ now()->format('m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td>1 (satu) berkas</td>
                    </tr>
                    <tr>
                        <td>Hal</td>
                        <td>:</td>
                        <td><strong>Laporan Daftar Kelompok</strong></td>
                    </tr>
                </table>
            </div>
            <div class="doc-tanggal">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>

        {{-- Judul Dokumen --}}
        <div class="doc-title-block">
            <h2>Laporan Daftar Kelompok</h2>
        </div>

        {{-- Ringkasan --}}
        <div class="summary-box">
            <table>
                <tr>
                    <td class="label">Periode Cetak</td>
                    <td>: {{ now()->translatedFormat('d F Y, H:i') }} WIB</td>
                </tr>
                <tr>
                    <td class="label">Total Kelompok</td>
                    <td>: <strong>{{ $kelompoks->count() }} Kelompok</strong></td>
                </tr>
                <tr>
                    <td class="label">Total Anggota Terdaftar</td>
                    <td>: <strong>{{ $kelompoks->sum('users_count') }} Orang</strong></td>
                </tr>
            </table>
        </div>

        {{-- Tabel Kelompok --}}
        <table class="main-table">
            <thead>
                <tr>
                    <th style="width:32px;">No.</th>
                    <th>Nama Kelompok</th>
                    <th class="td-jorong">Jorong</th>
                    <th class="td-jumlah">Jumlah Anggota</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelompoks as $i => $kelompok)
                <tr>
                    <td class="td-no">{{ $i + 1 }}.</td>
                    <td><strong>{{ $kelompok->name }}</strong></td>
                    <td style="text-align: center;">{{ $kelompok->jorong_label }}</td>
                    <td style="text-align: center;">{{ $kelompok->users_count }} Orang</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding:20px; color:#555; font-style:italic;">
                        Belum ada data kelompok.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</body>
</html>
