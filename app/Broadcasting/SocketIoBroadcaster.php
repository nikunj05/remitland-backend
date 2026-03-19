<?php

namespace App\Broadcasting;

use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SocketIoBroadcaster extends Broadcaster
{
    /**
     * The Socket.IO server URL.
     */
    protected string $url;

    /**
     * HTTP client options.
     */
    protected array $options;

    /**
     * Create a new broadcaster instance.
     */
    public function __construct(array $config)
    {
        $this->url = rtrim($config['url'], '/');
        $this->options = $config['options'] ?? [];
    }

    /**
     * Authenticate the incoming request for a given channel.
     */
    public function auth($request)
    {
        // For public channels, no authentication is needed
        if (str_starts_with($request->channel_name, 'public.')) {
            return true;
        }

        // For private and presence channels, use Laravel's default authentication
        return parent::verifyUserCanAccessChannel(
            $request,
            $request->channel_name
        );
    }

    /**
     * Return the valid authentication response.
     */
    public function validAuthenticationResponse($request, $result)
    {
        if (str_starts_with($request->channel_name, 'presence-')) {
            return ['auth' => $request->channel_name, 'channel_data' => json_encode($result)];
        }

        return ['auth' => $request->channel_name];
    }

    /**
     * Broadcast the given event.
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        try {
            $timeout = $this->options['timeout'] ?? 30;

            foreach ($channels as $channel) {
                $response = Http::timeout($timeout)->post($this->url . '/broadcast', [
                    'channel' => $channel,
                    'event' => $event,
                    'data' => $payload,
                ]);

                if (!$response->successful()) {
                    throw new \Exception("HTTP {$response->status()}: {$response->body()}");
                }
            }
        } catch (\Exception $e) {
            throw new BroadcastException('Socket.IO broadcasting failed: ' . $e->getMessage());
        }
    }

}
