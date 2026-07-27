<?php

namespace App\Services;

class WhatsAppKegiatanResponseParser
{
    public const VALID = 'valid';

    public const MISSING_REASON = 'missing_reason';

    public const INVALID = 'invalid';

    /**
     * @return array{result: string, status: ?string, alasan: ?string}
     */
    public function parse(string $body): array
    {
        $normalized = preg_replace('/\s+/u', ' ', trim($body)) ?? '';

        if (mb_strtolower($normalized) === 'bersedia') {
            return [
                'result' => self::VALID,
                'status' => 'bersedia',
                'alasan' => null,
            ];
        }

        if (preg_match('/^tidak\s+bersedia$/iu', $normalized) === 1) {
            return [
                'result' => self::MISSING_REASON,
                'status' => null,
                'alasan' => null,
            ];
        }

        if (preg_match('/^tidak\s+bersedia(?:\s*[:\-]\s*|\s+)(.*)$/iu', $normalized, $matches) === 1) {
            $alasan = trim($matches[1]);

            if ($alasan === '') {
                return [
                    'result' => self::MISSING_REASON,
                    'status' => null,
                    'alasan' => null,
                ];
            }

            return [
                'result' => self::VALID,
                'status' => 'tidak_bersedia',
                'alasan' => $alasan,
            ];
        }

        return [
            'result' => self::INVALID,
            'status' => null,
            'alasan' => null,
        ];
    }
}
