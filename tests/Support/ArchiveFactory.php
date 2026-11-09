<?php

declare(strict_types=1);

namespace Tests\Support;

use RuntimeException;
use ZipArchive;

/** Builds Binance-style archives (a zip with one CSV) and their CHECKSUM text for tests. */
final class ArchiveFactory
{
    /**
     * @param  list<string>  $lines
     * @return array{zip: string, checksum: string}
     */
    public static function make(string $fileName, array $lines): array
    {
        $path = sys_get_temp_dir().'/'.bin2hex(random_bytes(8)).'.zip';
        $zip = new ZipArchive;

        if ($zip->open($path, ZipArchive::CREATE) !== true) {
            throw new RuntimeException('Cannot create a test archive.');
        }

        $zip->addFromString(str_replace('.zip', '.csv', $fileName), implode("\n", $lines)."\n");
        $zip->close();
        $bytes = (string) file_get_contents($path);
        unlink($path);

        return ['zip' => $bytes, 'checksum' => hash('sha256', $bytes).'  '.$fileName];
    }

    /** One 1-minute spot kline line, in microseconds like the real spot archives. */
    public static function spotLine(int $openTimeMilliseconds, string $close = '100.5'): string
    {
        $open = $openTimeMilliseconds * 1000;

        return implode(',', [$open, '100.00000000', '101.00000000', '99.00000000', $close, '2.00000000', $open + 59_999_999, '201.00000000', 7, '1.00000000', '100.50000000', 0]);
    }
}
