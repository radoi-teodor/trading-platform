<?php

declare(strict_types=1);

namespace App\Agents\Mcp;

use App\Agents\Models\McpCall;
use App\Agents\Models\McpToken;
use App\Support\Clock\Clock;
use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use LogicException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Lets an MCP request through only when the endpoint is on and the request carries a live token (D-63), then
 * records the call: token name, method, tool, a hash of the arguments, duration and outcome. Never the token.
 */
final readonly class AuthenticateMcpToken
{
    public function __construct(private Repository $config, private Clock $clock) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->config->get('mcp.enabled') !== true) {
            abort(404);
        }

        $secret = $request->bearerToken();
        $hash = is_string($secret) ? hash('sha256', $secret) : '';
        $token = $hash === '' ? null : McpToken::query()->where('token_hash', $hash)->whereNull('revoked_at')->first();

        if ($token === null || ! hash_equals($token->token_hash, $hash)) {
            return response()->json(['error' => 'A valid bearer token is required (php artisan mcp:token).'], 401);
        }

        $request->attributes->set('mcp_token', $token);
        $started = hrtime(true);
        $response = $next($request);

        if (! $response instanceof Response) {
            throw new LogicException('The MCP route answered without a response.');
        }

        $body = json_decode((string) $request->getContent(), true);
        $body = is_array($body) ? $body : [];
        $params = is_array($body['params'] ?? null) ? $body['params'] : [];
        $result = json_decode((string) $response->getContent(), true);
        $failed = $response->getStatusCode() >= 400 || (is_array($result) && (isset($result['error']) || data_get($result, 'result.isError') === true));

        McpCall::query()->create([
            'token_name' => $token->name,
            'method' => is_string($body['method'] ?? null) ? $body['method'] : 'unknown',
            'tool' => is_string($params['name'] ?? null) ? $params['name'] : null,
            'arguments_hash' => hash('sha256', (string) json_encode($params['arguments'] ?? [])),
            'duration_ms' => intdiv(hrtime(true) - $started, 1_000_000),
            'outcome' => $failed ? 'error' : 'ok',
            'created_at' => $this->clock->now(),
        ]);
        $token->update(['last_used_at' => $this->clock->now()]);

        return $response;
    }
}
