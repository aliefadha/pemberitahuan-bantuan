<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Kegiatan - {{ $kegiatan->judul }}</title>
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

        /* Info Kegiatan Box */
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

        /* Rekapitulasi */
        .rekap-title {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 6px;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rekap-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9.5pt;
            margin-bottom: 16px;
        }

        .rekap-table td {
            padding: 4px 10px;
            border: 1px solid #999;
        }

        .rekap-table .rlabel {
            font-weight: bold;
            background: #eee;
            width: 50%;
        }

        .rekap-table .rbersedia {
            color: #000;
            font-weight: bold;
        }

        .rekap-table .rtidak {
            color: #000;
            font-weight: bold;
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
        .td-status { text-align: center; }
        .td-waktu { text-align: center; white-space: nowrap; font-size: 9pt; color: #333; }

        .status-bersedia {
            font-weight: bold;
            color: #000;
        }

        .status-tidak {
            font-weight: bold;
            color: #000;
        }



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

        {{-- Nomor, Sifat, Lampiran, Hal --}}
        @php
            $totalPeserta   = $pesertas->count();
            $bersedia       = collect($responses)->where('status', 'bersedia')->count();
            $tidakBersedia  = collect($responses)->where('status', 'tidak_bersedia')->count();
            $belumMerespons = $totalPeserta - $bersedia - $tidakBersedia;
        @endphp

        <div class="doc-header-row">
            <div class="doc-meta">
                <table>
                    <tr>
                        <td>Nomor</td>
                        <td>:</td>
                        <td>{{ now()->format('Y') }}/KEG/{{ $kegiatan->id }}/{{ now()->format('m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td>1 (satu) berkas</td>
                    </tr>
                    <tr>
                        <td>Hal</td>
                        <td>:</td>
                        <td><strong>Laporan Peserta Kegiatan</strong></td>
                    </tr>
                </table>
            </div>
            <div class="doc-tanggal">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>

        {{-- Judul Dokumen --}}
        <div class="doc-title-block">
            <h2>Laporan Peserta Kegiatan</h2>
        </div>

        {{-- Info Kegiatan --}}
        <div class="info-box">
            <div class="info-box-title">&#128197; Informasi Kegiatan</div>
            <div class="info-box-body">
                <table>
                    <tr>
                        <td>Nama Kegiatan</td>
                        <td>:</td>
                        <td><strong>{{ $kegiatan->judul }}</strong></td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{{ $kegiatan->tanggal->translatedFormat('l, d F Y') }}, Pukul {{ $kegiatan->tanggal->format('H:i') }} WIB</td>
                    </tr>
                    <tr>
                        <td>Jorong</td>
                        <td>:</td>
                        <td>{{ $kegiatan->jorong_label ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Deskripsi</td>
                        <td>:</td>
                        <td>{{ $kegiatan->deskripsi ?: '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Rekapitulasi --}}
        <div class="rekap-title">Rekapitulasi Kehadiran</div>
        <table class="rekap-table">
            <tr>
                <td class="rlabel">Total Peserta Merespons</td>
                <td><strong>{{ $totalPeserta }} orang</strong></td>
                <td class="rlabel">Bersedia Hadir</td>
                <td class="rbersedia">{{ $bersedia }} orang</td>
            </tr>
            <tr>
                <td class="rlabel">Persentase Kehadiran</td>
                <td><strong>{{ $totalPeserta > 0 ? round(($bersedia / $totalPeserta) * 100) : 0 }}%</strong></td>
                <td class="rlabel">Tidak Bersedia</td>
                <td class="rtidak">{{ $tidakBersedia }} orang</td>
            </tr>
        </table>

        @php
            $groupedPesertas = $pesertas->groupBy(function($user) {
                return $user->kelompok ? $user->kelompok->name : 'Tanpa Kelompok';
            })->sortBy(function($group, $key) {
                return $key === 'Tanpa Kelompok' ? 'zzzzzzz' : $key;
            });
        @endphp

        {{-- Grouped Tables --}}
        @forelse($groupedPesertas as $kelompokName => $members)
            <div style="margin-top: 20px; page-break-inside: avoid;">
                <div style="font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10.5pt; font-weight: bold; margin-bottom: 6px; border-bottom: 1.5px solid #000; padding-bottom: 3px; color: #000;">
                    {{ $kelompokName }} ({{ $members->count() }} orang)
                </div>
                <table class="main-table" style="margin-top: 0px; margin-bottom: 15px;">
                    <thead>
                        <tr>
                            <th style="width:32px;">No.</th>
                            <th>Nama Lengkap</th>
                            <th>Nomor WhatsApp</th>
                            <th style="width:100px;">Status Kehadiran</th>
                            <th style="width:110px;">Waktu Tanggapan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $j => $peserta)
                        @php
                            $response = $responses[$peserta->id] ?? null;
                            $status = $response ? $response->status : 'belum_menanggapi';
                            $updatedAt = $response ? \Carbon\Carbon::parse($response->updated_at) : null;
                        @endphp
                        <tr>
                            <td class="td-no">{{ $j + 1 }}.</td>
                            <td><strong>{{ $peserta->name }}</strong></td>
                            <td>{{ $peserta->no_telepon ?? '-' }}</td>
                            <td class="td-status">
                                @if($status === 'bersedia')
                                    <span class="status-bersedia">&#10003; Bersedia</span>
                                @elseif($status === 'tidak_bersedia')
                                    <span class="status-tidak">&#10007; Tidak Bersedia</span>
                                @else
                                    <span style="color: #666; font-style: italic;">Belum Merespons</span>
                                @endif
                            </td>
                            <td class="td-waktu">{{ $updatedAt ? $updatedAt->translatedFormat('d/m/Y') . ' ' . $updatedAt->format('H:i') . ' WIB' : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <div style="text-align:center; padding:20px; color:#555; font-style:italic; border: 1px dashed #999; margin-top: 20px;">
                Tidak ada data peserta di jorong ini.
            </div>
        @endforelse
    </div>

</body>
</html>
