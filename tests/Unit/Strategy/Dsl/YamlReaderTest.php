<?php

declare(strict_types=1);

use App\Strategy\Dsl\DslError;
use App\Strategy\Dsl\Yaml\YamlList;
use App\Strategy\Dsl\Yaml\YamlMap;
use App\Strategy\Dsl\Yaml\YamlReader;
use App\Strategy\Dsl\Yaml\YamlScalar;

mutates(YamlReader::class);

/** Plain PHP values of a document, scalars as their exact text, for comparisons. */
function plainYaml(YamlMap|YamlList|YamlScalar $value): mixed
{
    return match (true) {
        $value instanceof YamlScalar => $value->text,
        $value instanceof YamlList => array_map(plainYaml(...), $value->items),
        default => array_map(plainYaml(...), $value->entries),
    };
}

it('reads the strategy document subset of YAML, keeping every scalar as written', function (): void {
    $document = YamlReader::read(<<<'YAML'
        strategy: btc-trend-4h                # id
        title: BTC trend follower on 4h
        hypothesis: >
          Crypto trends persist after a cross.
          Paid by late followers.
        notes: |
          line one
          line two
        market:
          type: spot
          instruments: [BTC/USDT, "ETH/USDT"]
        params:
          fast: { default: 0.50, range: [0.30, 0.70], step: 0.05 }
        filters:
          - adx(14) > 20
          - natr(14) < 3
        entries:
          long:
            when: ema(20) crosses_above ema(50) and close > ema(200) on 1d
        quoted: 'it''s # not a comment'
        empty_list: []
        YAML);

    expect(plainYaml($document))->toBe([
        'strategy' => 'btc-trend-4h',
        'title' => 'BTC trend follower on 4h',
        'hypothesis' => "Crypto trends persist after a cross. Paid by late followers.\n",
        'notes' => "line one\nline two\n",
        'market' => ['type' => 'spot', 'instruments' => ['BTC/USDT', 'ETH/USDT']],
        'params' => ['fast' => ['default' => '0.50', 'range' => ['0.30', '0.70'], 'step' => '0.05']],
        'filters' => ['adx(14) > 20', 'natr(14) < 3'],
        'entries' => ['long' => ['when' => 'ema(20) crosses_above ema(50) and close > ema(200) on 1d']],
        'quoted' => "it's # not a comment",
        'empty_list' => [],
    ]);
});

it('knows where every value starts', function (): void {
    $document = YamlReader::read("strategy: x\nexits:\n  stop_loss: 2 * atr(14)\nfilters:\n  - adx(14) > 20\nparams:\n  fast: { default: 20 }\n");
    $exits = $document->entries['exits'];
    $filters = $document->entries['filters'];
    $params = $document->entries['params'];
    $stop = $exits instanceof YamlMap ? $exits->entries['stop_loss'] : null;
    $filter = $filters instanceof YamlList ? $filters->items[0] : null;
    $fast = $params instanceof YamlMap ? $params->entries['fast'] : null;
    $default = $fast instanceof YamlMap ? $fast->entries['default'] : null;

    expect($stop?->position()->describe())->toBe('line 3, column 14')
        ->and($filter?->position()->describe())->toBe('line 5, column 5')
        ->and($default?->position()->describe())->toBe('line 7, column 20')
        ->and($exits->position()->describe())->toBe('line 2, column 1')
        ->and($document->keyPosition('exits')?->describe())->toBe('line 2, column 1');
});

it('refuses what it does not read, with the place', function (string $source, string $message): void {
    expect(fn () => YamlReader::read($source))->toThrow(DslError::class, $message);
})->with([
    'tab indentation' => ["a:\n\tb: 1", 'line 2, column 1: indent with spaces, not tabs.'],
    'duplicate key' => ["a: 1\na: 2", 'line 2, column 1: a is written twice.'],
    'not a key' => ["a: 1\njust text", 'line 2, column 1: expected key: value.'],
    'bad indentation' => ["a:\n  b: 1\n c: 2", 'line 3, column 2: this line is indented differently from the lines above it.'],
    'unclosed flow' => ['a: [1, 2', 'line 1, column 4: [ is not closed on this line.'],
    'unclosed quote' => ['a: "text', 'line 1, column 4: the quoted text is not closed on this line.'],
    'anchors' => ['a: &x 1', 'line 1, column 4: anchors, aliases and tags are not used in strategy documents.'],
    'list mixed with keys' => ["a:\n  - 1\n  b: 2", 'line 3, column 3: a list cannot mix items and keys.'],
    'empty document' => ['', 'line 1, column 1: the document is empty.'],
]);
