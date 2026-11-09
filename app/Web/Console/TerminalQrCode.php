<?php

declare(strict_types=1);

namespace App\Web\Console;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;

/**
 * Draws a QR code in a terminal (D-86), so the operator scans the authenticator key with a phone. One text line holds
 * two rows of modules (half blocks), with a light border of four modules as the QR standard asks.
 */
final class TerminalQrCode
{
    private const int BORDER = 4;

    /**
     * With colours, each cell is an upper half block whose foreground is the upper module and background the lower
     * one, in black and bright white, so the code reads the same on a dark or a light theme. Without colours, light
     * modules are drawn and dark ones left blank, which reads right on the dark background of cmd and PowerShell.
     */
    public function render(string $text, bool $colours): string
    {
        $modules = $this->modules($text);
        $drawing = '';

        for ($y = 0; $y < count($modules); $y += 2) {
            $line = '';

            foreach ($modules[$y] as $x => $upper) {
                $lower = $modules[$y + 1][$x];
                $line .= $colours ? self::colouredCell($upper, $lower) : self::plainCell($upper, $lower);
            }

            $drawing .= $line.($colours ? "\e[0m" : '')."\n";
        }

        return $drawing;
    }

    /**
     * The code's modules (1 dark, 0 light) inside the border, padded with a light row to an even height.
     *
     * @return list<list<int>>
     */
    private function modules(string $text): array
    {
        $matrix = Encoder::encode($text, ErrorCorrectionLevel::M())->getMatrix();
        $width = $matrix->getWidth();
        $size = $width + 2 * self::BORDER;
        $rows = [];

        for ($y = 0; $y < $size + ($size % 2); $y++) {
            $row = [];

            for ($x = 0; $x < $size; $x++) {
                $inside = $x >= self::BORDER && $y >= self::BORDER && $x < self::BORDER + $width && $y < self::BORDER + $width;
                $row[] = $inside ? $matrix->get($x - self::BORDER, $y - self::BORDER) : 0;
            }

            $rows[] = $row;
        }

        return $rows;
    }

    private static function colouredCell(int $upper, int $lower): string
    {
        return "\e[".($upper === 1 ? '30' : '97').';'.($lower === 1 ? '40' : '107').'m▀';
    }

    private static function plainCell(int $upper, int $lower): string
    {
        return match ([$upper, $lower]) {
            [0, 0] => '█',
            [0, 1] => '▀',
            [1, 0] => '▄',
            default => ' ',
        };
    }
}
