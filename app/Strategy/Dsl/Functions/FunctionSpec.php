<?php

declare(strict_types=1);

namespace App\Strategy\Dsl\Functions;

use App\Strategy\Dsl\Types\Type;

/**
 * A function or a name of the DSL. $fields lists the fields of a function with several outputs (bb(20).upper); a
 * function whose $followsSource is true measures what its src measures (sma of close is a price, sma of volume a
 * volume). $refused, when set, is why the compiler refuses it.
 */
final readonly class FunctionSpec
{
    /**
     * @param  list<ParamSpec>  $params
     * @param  array<string, Type>  $fields
     * @param  list<string>  $values  the values a text function can take
     */
    public function __construct(
        public string $name,
        public Group $group,
        public ?Type $result,
        public array $params = [],
        public array $fields = [],
        public bool $isCall = true,
        public bool $followsSource = false,
        public ?string $refused = null,
        public array $values = [],
    ) {}

    public function param(string $name): ?ParamSpec
    {
        foreach ($this->params as $param) {
            if ($param->name === $name) {
                return $param;
            }
        }

        return null;
    }
}
