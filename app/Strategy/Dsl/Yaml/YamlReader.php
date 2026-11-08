<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Yaml;

use App\Strategy\Dsl\DslError;
use App\Strategy\Dsl\Position;

/**
 * Reads the subset of YAML that strategy documents use (D-51): block mappings and lists, flow lists and mappings on
 * one line, quoted and plain scalars, folded (>) and literal (|) text, comments. Every scalar keeps its exact text
 * and position, so 0.30 stays "0.30" and errors point where the operator typed. Anchors, aliases, tags and
 * multi-document files are refused.
 */
final class YamlReader
{
    /** @var list<array{line: int, indent: int, content: string, raw: string}> */
    private array $lines = [];

    private int $index = 0;

    private function __construct(string $source)
    {
        foreach (preg_split('/\r?\n/', $source) ?: [] as $number => $raw) {
            $indent = strlen($raw) - strlen(ltrim($raw, ' '));

            if (isset($raw[$indent]) && $raw[$indent] === "\t") {
                throw new DslError(new Position($number + 1, 1), 'indent with spaces, not tabs.');
            }

            $this->lines[] = ['line' => $number + 1, 'indent' => $indent, 'content' => rtrim(self::withoutComment(substr($raw, $indent))), 'raw' => $raw];
        }
    }

    public static function read(string $source): YamlMap
    {
        $reader = new self($source);
        $first = $reader->nextSignificant();

        if ($first === null) {
            throw new DslError(new Position(1, 1), 'the document is empty.');
        }

        $document = $reader->block($reader->lines[$first]['indent'], new Position(1, 1));
        $left = $reader->nextSignificant();

        if ($left !== null) {
            $line = $reader->lines[$left];

            throw new DslError(new Position($line['line'], $line['indent'] + 1), 'this line is indented differently from the lines above it.');
        }

        if (! $document instanceof YamlMap) {
            throw new DslError(new Position(1, 1), 'a strategy document is a set of keys, such as strategy: my-strategy.');
        }

        return $document;
    }

    /** A block mapping or list whose lines start at $indent. */
    private function block(int $indent, Position $at): YamlMap|YamlList
    {
        $first = $this->nextSignificant();
        $isList = $first !== null && self::isItem($this->lines[$first]['content']);

        return $isList ? $this->blockList($indent, $at) : $this->blockMap($indent, $at);
    }

    private function blockMap(int $indent, Position $at): YamlMap
    {
        $entries = [];
        $keys = [];

        while (($i = $this->nextSignificant()) !== null && $this->lines[$i]['indent'] >= $indent) {
            $line = $this->lines[$i];
            $here = new Position($line['line'], $line['indent'] + 1);

            if ($line['indent'] > $indent) {
                throw new DslError($here, 'this line is indented differently from the lines above it.');
            }

            if (self::isItem($line['content'])) {
                throw new DslError($here, 'a list cannot mix items and keys.');
            }

            if (preg_match('/^([A-Za-z_][A-Za-z0-9_-]*)\s*:(?:\s+(.*))?$/', $line['content'], $match) !== 1) {
                throw new DslError($here, 'expected key: value.');
            }

            $key = $match[1];

            if (isset($entries[$key])) {
                throw new DslError($here, "{$key} is written twice.");
            }

            $this->index = $i + 1;
            $value = $match[2] ?? '';
            $keys[$key] = $here;
            $entries[$key] = match (true) {
                $value === '>' || $value === '|' => $this->blockText($indent, $value === '>', $here),
                $value !== '' => $this->inline($value, new Position($line['line'], strlen($line['raw']) - strlen(ltrim(substr($line['raw'], $line['indent'] + strlen($key) + 1))) + 1)),
                default => $this->child($indent, $here),
            };
        }

        return new YamlMap($entries, $keys, $at);
    }

    private function blockList(int $indent, Position $at): YamlList
    {
        $items = [];

        while (($i = $this->nextSignificant()) !== null && $this->lines[$i]['indent'] >= $indent) {
            $line = $this->lines[$i];
            $here = new Position($line['line'], $line['indent'] + 1);

            if ($line['indent'] > $indent) {
                throw new DslError($here, 'this line is indented differently from the lines above it.');
            }

            if (! self::isItem($line['content'])) {
                throw new DslError($here, 'a list cannot mix items and keys.');
            }

            $this->index = $i + 1;
            $text = ltrim(substr($line['content'], 1));
            $items[] = $this->inline($text, new Position($line['line'], $line['indent'] + 1 + strlen($line['content']) - strlen($text)));
        }

        return new YamlList($items, $at);
    }

    /** The value under a key written alone on its line: a deeper block, or an empty scalar. */
    private function child(int $indent, Position $at): YamlScalar|YamlList|YamlMap
    {
        $next = $this->nextSignificant();

        if ($next === null || $this->lines[$next]['indent'] <= $indent) {
            return new YamlScalar('', $at);
        }

        return $this->block($this->lines[$next]['indent'], $at);
    }

    private function blockText(int $indent, bool $folded, Position $at): YamlScalar
    {
        $parts = [];
        $textIndent = null;

        for (; $this->index < count($this->lines); $this->index++) {
            $line = $this->lines[$this->index];

            if (trim($line['raw']) === '') {
                $parts[] = '';

                continue;
            }

            if ($line['indent'] <= $indent) {
                break;
            }

            $textIndent ??= $line['indent'];
            $parts[] = substr($line['raw'], min($textIndent, $line['indent']));
        }

        while ($parts !== [] && end($parts) === '') {
            array_pop($parts);
        }

        $text = $folded ? preg_replace('/(?<=\S)\n(?=\S)/', ' ', implode("\n", $parts)) ?? '' : implode("\n", $parts);

        return new YamlScalar($text."\n", $at);
    }

    /** A value written after a key or a dash: a flow list or mapping, a quoted or a plain scalar. */
    private function inline(string $text, Position $at): YamlScalar|YamlList|YamlMap
    {
        if (in_array($text[0], ['&', '*', '!'], true)) {
            throw new DslError($at, 'anchors, aliases and tags are not used in strategy documents.');
        }

        $offset = 0;
        $value = self::flowValue($text, $offset, $at, false);

        if (trim(substr($text, $offset)) !== '') {
            throw new DslError(new Position($at->line, $at->column + $offset), 'unexpected text after the value.');
        }

        return $value;
    }

    private static function flowValue(string $text, int &$offset, Position $at, bool $inFlow): YamlScalar|YamlList|YamlMap
    {
        self::skipSpaces($text, $offset);
        $here = new Position($at->line, $at->column + $offset);
        $char = $text[$offset] ?? '';

        if ($char === '[' || $char === '{') {
            $close = $char === '[' ? ']' : '}';
            $offset++;
            $list = [];
            $map = [];
            $keys = [];

            while (true) {
                self::skipSpaces($text, $offset);

                if (! isset($text[$offset])) {
                    throw new DslError($here, "{$char} is not closed on this line.");
                }

                if ($text[$offset] === $close) {
                    $offset++;

                    return $char === '[' ? new YamlList($list, $here) : new YamlMap($map, $keys, $here);
                }

                if ($char === '{') {
                    $keyAt = new Position($at->line, $at->column + $offset);

                    if (preg_match('/\G([A-Za-z_][A-Za-z0-9_-]*)\s*:\s*/', $text, $match, 0, $offset) !== 1) {
                        throw new DslError($keyAt, 'expected key: value inside { }.');
                    }

                    $offset += strlen($match[0]);
                    $keys[$match[1]] = $keyAt;
                    $map[$match[1]] = self::flowValue($text, $offset, $at, true);
                } else {
                    $list[] = self::flowValue($text, $offset, $at, true);
                }

                self::skipSpaces($text, $offset);

                if (($text[$offset] ?? '') === ',') {
                    $offset++;
                } elseif (($text[$offset] ?? '') !== $close) {
                    throw new DslError($here, "{$char} is not closed on this line.");
                }
            }
        }

        if ($char === '"' || $char === "'") {
            $end = $offset + 1;
            $value = '';

            while (true) {
                if (! isset($text[$end])) {
                    throw new DslError($here, 'the quoted text is not closed on this line.');
                }

                if ($text[$end] === $char) {
                    if ($char === "'" && ($text[$end + 1] ?? '') === "'") {
                        $value .= "'";
                        $end += 2;

                        continue;
                    }

                    break;
                }

                $value .= $text[$end];
                $end++;
            }

            $offset = $end + 1;

            return new YamlScalar($value, $here, true);
        }

        $stop = $inFlow ? '/\G[^,\]\}]*/' : '/\G.*/';
        preg_match($stop, $text, $match, 0, $offset);
        $raw = $match[0] ?? '';
        $offset += strlen($raw);

        return new YamlScalar(trim($raw), $here);
    }

    private static function skipSpaces(string $text, int &$offset): void
    {
        while (($text[$offset] ?? '') === ' ') {
            $offset++;
        }
    }

    private static function isItem(string $content): bool
    {
        return $content === '-' || str_starts_with($content, '- ');
    }

    /** The line without a comment: a # at the start or after a space, outside quotes. */
    private static function withoutComment(string $text): string
    {
        $quote = null;

        for ($i = 0, $length = strlen($text); $i < $length; $i++) {
            $char = $text[$i];

            if ($quote !== null) {
                $quote = $char === $quote ? null : $quote;
            } elseif ($char === '"' || $char === "'") {
                $quote = $char;
            } elseif ($char === '#' && ($i === 0 || $text[$i - 1] === ' ')) {
                return substr($text, 0, $i);
            }
        }

        return $text;
    }

    /** The index of the next line with content, from the current one, or null at the end. */
    private function nextSignificant(): ?int
    {
        for ($i = $this->index; $i < count($this->lines); $i++) {
            if ($this->lines[$i]['content'] !== '') {
                return $this->index = $i;
            }
        }

        $this->index = count($this->lines);

        return null;
    }
}
