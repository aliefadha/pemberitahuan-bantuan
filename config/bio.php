<?php

return [
    'sections' => [
        [
            'title' => 'Jumlah Anggota Keluarga',
            'questions' => [
                [
                    'key' => 'jumlah_anggota_keluarga',
                    'question' => 'Jumlah anggota keluarga',
                    'type' => 'integer',
                    'min' => 0,
                ],
                [
                    'key' => 'jumlah_balita',
                    'question' => 'Jumlah balita',
                    'type' => 'integer',
                    'min' => 0,
                ],
                [
                    'key' => 'jumlah_pasangan_usia_subur',
                    'question' => 'Jumlah pasangan usia subur',
                    'type' => 'integer',
                    'min' => 0,
                ],
                [
                    'key' => 'jumlah_wanita_usia_subur',
                    'question' => 'Jumlah wanita usia subur',
                    'type' => 'integer',
                    'min' => 0,
                ],
                [
                    'key' => 'jumlah_ibu_hamil',
                    'question' => 'Jumlah ibu hamil',
                    'type' => 'integer',
                    'min' => 0,
                ],
                [
                    'key' => 'jumlah_ibu_menyusui',
                    'question' => 'Jumlah ibu menyusui',
                    'type' => 'integer',
                    'min' => 0,
                ],
                [
                    'key' => 'jumlah_lansia',
                    'question' => 'Jumlah lansia',
                    'type' => 'integer',
                    'min' => 0,
                ],
                [
                    'key' => 'jumlah_3_buta',
                    'question' => 'Jumlah 3 buta',
                    'type' => 'integer',
                    'min' => 0,
                ],
                [
                    'key' => 'jumlah_berkebutuhan_khusus',
                    'question' => 'Jumlah berkebutuhan khusus',
                    'type' => 'integer',
                    'min' => 0,
                ],
            ],
        ],
        [
            'title' => 'Kriteria Rumah',
            'questions' => [
                [
                    'key' => 'sehat_layak_huni',
                    'question' => 'Sehat layak huni',
                    'type' => 'boolean',
                ],
                [
                    'key' => 'tidak_layak_huni',
                    'question' => 'Tidak layak huni',
                    'type' => 'boolean',
                ],
                [
                    'key' => 'memiliki_tempat_pembuangan_sampah',
                    'question' => 'Memiliki tempat pembuangan sampah',
                    'type' => 'boolean',
                ],
                [
                    'key' => 'memiliki_spal',
                    'question' => 'Memiliki SPAL',
                    'type' => 'boolean',
                ],
                [
                    'key' => 'memiliki_jamban',
                    'question' => 'Memiliki jamban',
                    'type' => 'boolean',
                ],
                [
                    'key' => 'menempel_stiker_p4k',
                    'question' => 'Menempel stiker P4K',
                    'type' => 'boolean',
                ],
            ],
        ],
        [
            'title' => 'Makanan Pokok',
            'questions' => [
                [
                    'key' => 'beras',
                    'question' => 'Beras',
                    'type' => 'boolean',
                ],
                [
                    'key' => 'non_beras',
                    'question' => 'Non beras',
                    'type' => 'boolean',
                ],
            ],
        ],
    ],
];
