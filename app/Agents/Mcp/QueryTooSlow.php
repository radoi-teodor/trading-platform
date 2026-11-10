<?php

declare(strict_types=1);

namespace App\Agents\Mcp;

use RuntimeException;

/** A research tool's read ran into the statement bound (D-93); its message is what the agent is told. */
final class QueryTooSlow extends RuntimeException {}
