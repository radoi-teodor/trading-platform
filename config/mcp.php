<?php

declare(strict_types=1);

// The MCP endpoint for external agents (D-63). Off unless MCP_ENABLED=true; tokens come from `php artisan mcp:token`.
// No secret lives here.

return [
    'enabled' => (bool) env('MCP_ENABLED', false),

    // Calls each token may make per minute.
    'calls_per_minute' => (int) env('MCP_CALLS_PER_MINUTE', 60),

    // laravel/mcp's OAuth settings, unused: this endpoint authenticates with bearer tokens only.
    'redirect_domains' => [],
    'custom_schemes' => [],
];
