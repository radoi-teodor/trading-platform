<?php

declare(strict_types=1);

namespace App\Agents\Mcp;

use App\Support\Number\Decimal;
use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;
use LogicException;

/**
 * Runs what a research tool reads under a bound on each statement (D-93): an agent's question never holds the
 * database for longer than mcp.statement_timeout_ms, waiting for a lock included. The bound lasts for the read
 * only; what the connection had before is put back.
 */
final readonly class BoundedRead
{
    /** PostgreSQL's query_canceled, which is how a statement over its timeout ends. */
    private const string CANCELLED = '57014';

    public function __construct(private ConnectionInterface $db, private Repository $config) {}

    /**
     * @template T
     *
     * @param  Closure(): T  $read
     * @return T
     */
    public function run(Closure $read): mixed
    {
        $bound = $this->config->get('mcp.statement_timeout_ms');

        if (! is_int($bound) || $bound < 1) {
            throw new LogicException('mcp.statement_timeout_ms is a whole number of milliseconds above zero.');
        }

        try {
            return $this->db->transaction(function () use ($read, $bound): mixed {
                $before = $this->db->selectOne("select current_setting('statement_timeout') as bound");
                $before = is_object($before) && is_string($before->bound ?? null) ? $before->bound : throw new LogicException('The connection did not say its statement timeout.');
                $this->db->select("select set_config('statement_timeout', ?, true)", [$bound.'ms']);
                $answer = $read();
                $this->db->select("select set_config('statement_timeout', ?, true)", [$before]);

                return $answer;
            });
        } catch (QueryException $failure) {
            if (($failure->errorInfo[0] ?? null) !== self::CANCELLED) {
                throw $failure;
            }

            $seconds = Decimal::of($bound)->dividedBy(Decimal::of(1000), 3);

            throw new QueryTooSlow("The data took longer than {$seconds} s to read; ask for a shorter window or fewer rows.", previous: $failure);
        }
    }
}
