<?php

declare(strict_types=1);

// The Graphite identity (D-24, docs/brand.md): every token exists in both themes, every text color meets
// WCAG 2.2 AA on the surfaces it is used on, and no color literal exists outside the token file (A1).

const TOKENS_FILE = __DIR__.'/../../resources/css/tokens.css';

const REQUIRED_TOKENS = [
    'bg', 'surface', 'surface-2', 'border', 'border-strong', 'text', 'text-muted', 'text-subtle',
    'accent', 'accent-contrast', 'focus', 'up', 'down', 'up-alt', 'down-alt', 'warn', 'info', 'live', 'agent',
    'series-1', 'series-2', 'series-3', 'series-4', 'series-5',
];

const THEMES = ['dark', 'light'];

/**
 * @return array<string, string> token name => hex color
 */
function themeTokens(string $theme): array
{
    expect(TOKENS_FILE)->toBeFile();
    $css = (string) file_get_contents(TOKENS_FILE);

    $pattern = '/\[data-theme="'.preg_quote($theme, '/').'"\][^{]*\{(?<body>[^}]*)\}/';
    expect(preg_match($pattern, $css, $block))->toBe(1, "No [data-theme=\"{$theme}\"] block in tokens.css");

    preg_match_all('/--(?<name>[a-z0-9-]+)\s*:\s*(?<value>#[0-9a-fA-F]{6})\s*;/', $block['body'], $matches);

    return array_combine($matches['name'], $matches['value']);
}

function relativeLuminance(string $hex): float
{
    $channels = array_map(
        static fn (string $pair): float => hexdec($pair) / 255,
        str_split(ltrim($hex, '#'), 2),
    );
    $linear = array_map(
        static fn (float $c): float => $c <= 0.03928 ? $c / 12.92 : (($c + 0.055) / 1.055) ** 2.4,
        $channels,
    );

    return 0.2126 * $linear[0] + 0.7152 * $linear[1] + 0.0722 * $linear[2];
}

function contrastRatio(string $foreground, string $background): float
{
    $a = relativeLuminance($foreground);
    $b = relativeLuminance($background);

    return (max($a, $b) + 0.05) / (min($a, $b) + 0.05);
}

/**
 * Pairs from docs/brand.md, B4: [foreground, backgrounds, minimum ratio].
 *
 * @return list<array{0: string, 1: list<string>, 2: float}>
 */
function contrastRules(): array
{
    $textOnAll = ['surface', 'surface-2', 'bg'];

    return [
        ['text', $textOnAll, 7.0],
        ['text-muted', $textOnAll, 4.5],
        ['text-subtle', ['surface', 'surface-2'], 3.0],
        ['accent', $textOnAll, 4.5],
        ['up', $textOnAll, 4.5],
        ['down', $textOnAll, 4.5],
        ['up-alt', $textOnAll, 4.5],
        ['down-alt', $textOnAll, 4.5],
        ['warn', $textOnAll, 4.5],
        ['info', $textOnAll, 4.5],
        ['live', $textOnAll, 4.5],
        ['agent', $textOnAll, 4.5],
        ['accent-contrast', ['accent'], 4.5],
        ['border-strong', ['surface'], 3.0],
        ['focus', ['surface'], 3.0],
    ];
}

it('defines every token as a color in each theme', function (string $theme): void {
    expect(array_keys(themeTokens($theme)))->toContain(...REQUIRED_TOKENS);
})->with(THEMES);

it('meets the contrast rules of docs/brand.md', function (string $theme): void {
    $tokens = themeTokens($theme);

    foreach (contrastRules() as [$foreground, $backgrounds, $minimum]) {
        foreach ($backgrounds as $background) {
            $ratio = contrastRatio($tokens[$foreground], $tokens[$background]);

            expect($ratio)->toBeGreaterThanOrEqual(
                $minimum,
                sprintf('%s on %s in %s is %.2f:1, needs %.1f:1', $foreground, $background, $theme, $ratio, $minimum),
            );
        }
    }
})->with(THEMES);

it('keeps color literals inside the token file only', function (): void {
    $offenders = [];
    $roots = [__DIR__.'/../../resources/views', __DIR__.'/../../resources/js', __DIR__.'/../../resources/css'];

    foreach ($roots as $root) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));

        foreach ($files as $file) {
            if (! $file instanceof SplFileInfo || realpath($file->getPathname()) === realpath(TOKENS_FILE)) {
                continue;
            }

            $content = (string) file_get_contents($file->getPathname());

            if (preg_match('/#[0-9a-fA-F]{3,8}\b|\b(?:rgb|rgba|hsl|hsla|oklch)\(/', $content) === 1) {
                $offenders[] = $file->getPathname();
            }
        }
    }

    expect($offenders)->toBe([]);
});
