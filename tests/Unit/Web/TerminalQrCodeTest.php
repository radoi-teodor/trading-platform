<?php

declare(strict_types=1);

use App\Web\Console\TerminalQrCode;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;

const OTPAUTH = 'otpauth://totp/Trading%20X:op%40example.test?secret=JBSWY3DPEHPK3PXPJBSWY3DPEHPK3PXP&issuer=Trading%20X';

/**
 * The code as it must appear: bacon's modules (1 dark) inside a light border of 4, padded with a light row to an
 * even height, since one text line holds two rows.
 *
 * @return list<list<int>>
 */
function expectedModules(string $text): array
{
    $matrix = Encoder::encode($text, ErrorCorrectionLevel::M())->getMatrix();
    $size = $matrix->getWidth() + 8;
    $rows = [];

    for ($y = 0; $y < $size + ($size % 2); $y++) {
        $row = [];

        for ($x = 0; $x < $size; $x++) {
            $inside = $x >= 4 && $y >= 4 && $x < $size - 4 && $y < $size - 4;
            $row[] = $inside ? $matrix->get($x - 4, $y - 4) : 0;
        }

        $rows[] = $row;
    }

    return $rows;
}

/**
 * Reads the plain drawing back into modules: light modules are drawn, dark ones are spaces.
 *
 * @return list<list<int>>
 */
function readPlain(string $drawing): array
{
    $halves = ['█' => [0, 0], '▀' => [0, 1], '▄' => [1, 0], ' ' => [1, 1]];
    $rows = [];

    foreach (explode("\n", rtrim($drawing, "\n")) as $line) {
        $upper = [];
        $lower = [];

        foreach (mb_str_split($line) as $char) {
            [$upper[], $lower[]] = $halves[$char] ?? throw new UnexpectedValueException("Unexpected character '{$char}'.");
        }

        $rows[] = $upper;
        $rows[] = $lower;
    }

    return $rows;
}

/**
 * Reads the coloured drawing back into modules: each cell is an upper half block, its foreground the upper
 * module and its background the lower one, black for dark and bright white for light.
 *
 * @return list<list<int>>
 */
function readColoured(string $drawing): array
{
    $foreground = ['30' => 1, '97' => 0];
    $background = ['40' => 1, '107' => 0];
    $rows = [];

    foreach (explode("\n", rtrim($drawing, "\n")) as $line) {
        expect($line)->toEndWith("\e[0m");
        preg_match_all('/\e\[(\d+);(\d+)m▀/u', $line, $cells, PREG_SET_ORDER);
        expect(preg_replace('/\e\[\d+;\d+m▀/u', '', $line))->toBe("\e[0m");

        $rows[] = array_map(fn (array $cell): int => $foreground[$cell[1]], $cells);
        $rows[] = array_map(fn (array $cell): int => $background[$cell[2]], $cells);
    }

    return $rows;
}

it('draws the code with half blocks for a dark terminal, with a light border of four modules', function (): void {
    $drawing = (new TerminalQrCode)->render(OTPAUTH, colours: false);

    expect(readPlain($drawing))->toBe(expectedModules(OTPAUTH));
});

it('draws the code in black and white whatever the terminal theme when the console has colours', function (): void {
    $drawing = (new TerminalQrCode)->render(OTPAUTH, colours: true);

    expect(readColoured($drawing))->toBe(expectedModules(OTPAUTH));
});

it('fits a default console window: one text line for two rows of modules', function (): void {
    $lines = explode("\n", rtrim((new TerminalQrCode)->render(OTPAUTH, colours: false), "\n"));
    $size = Encoder::encode(OTPAUTH, ErrorCorrectionLevel::M())->getMatrix()->getWidth() + 8;

    expect($lines)->toHaveCount(intdiv($size + 1, 2))
        ->and(mb_strlen($lines[0]))->toBe($size)
        ->and(count($lines))->toBeLessThan(30);
});
