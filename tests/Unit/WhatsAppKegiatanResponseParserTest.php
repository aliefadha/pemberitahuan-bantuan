<?php

use App\Services\WhatsAppKegiatanResponseParser;

it('parses a bersedia response case insensitively', function () {
    $result = (new WhatsAppKegiatanResponseParser)->parse("  BeRSeDiA \n");

    expect($result)->toBe([
        'result' => WhatsAppKegiatanResponseParser::VALID,
        'status' => 'bersedia',
        'alasan' => null,
    ]);
});

it('parses a tidak bersedia response with a reason', function (string $body, string $reason) {
    $result = (new WhatsAppKegiatanResponseParser)->parse($body);

    expect($result)->toBe([
        'result' => WhatsAppKegiatanResponseParser::VALID,
        'status' => 'tidak_bersedia',
        'alasan' => $reason,
    ]);
})->with([
    ['tidak bersedia karena sakit', 'karena sakit'],
    ['TIDAK BERSEDIA: ada keperluan keluarga', 'ada keperluan keluarga'],
    ['tidak   bersedia - dinas luar', 'dinas luar'],
]);

it('requires a reason for tidak bersedia', function (string $body) {
    $result = (new WhatsAppKegiatanResponseParser)->parse($body);

    expect($result['result'])->toBe(WhatsAppKegiatanResponseParser::MISSING_REASON);
})->with([
    'tidak bersedia',
    'tidak bersedia:',
    'tidak bersedia - ',
]);

it('rejects unrecognized responses', function (string $body) {
    $result = (new WhatsAppKegiatanResponseParser)->parse($body);

    expect($result['result'])->toBe(WhatsAppKegiatanResponseParser::INVALID);
})->with([
    'ya',
    'bersedia karena bisa hadir',
    '',
]);
