<?php

declare(strict_types=1);

namespace App\Agents\Mcp;

use RuntimeException;

/** A research tool cannot answer what was asked; its message tells the agent why and what to ask instead. */
final class Refused extends RuntimeException {}
