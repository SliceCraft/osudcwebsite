<?php

namespace App\Libraries\OsuAPI;

use Illuminate\Support\Facades\Http;

class OsuAPIClient
{
    protected string $accessToken;
    protected int $lastRequestTimestamp = 0;

    protected function ensureCredentials(): void
    {
        if(!empty($this->accessToken)) return;

        $response = Http::accept('application/json')
            ->contentType('application/json')
            ->post('https://osu.ppy.sh/oauth/token', [
                'client_id' => config('app.osu_api.client_id'),
                'client_secret' => config('app.osu_api.client_secret'),
                'grant_type' => 'client_credentials',
                'scope' => 'public'
            ])->json();

        $this->accessToken = $response['access_token'];
    }

    protected function ensureCooldown(int $delayInMs = 1000): void
    {
        $timeSinceLastRequest = (microtime(true) - ($this->lastRequestTimestamp)) * 1000;
        $sleepTime = ($delayInMs) - $timeSinceLastRequest;

        if($sleepTime > 0) {
            usleep($sleepTime * 1000);
        }
    }

    public function getRooms()
    {
        $this->ensureCredentials();

        $response = Http::accept('application/json')
            ->contentType('application/json')
            ->withHeader('Authorization', 'Bearer '.$this->accessToken)
            ->get('https://osu.ppy.sh/api/v2/rooms', ['mode' => 'all'])
            ->json();

        return $response;
    }

    public function getRoom(int $roomId)
    {
        $this->ensureCredentials();
        $this->ensureCooldown();

        $response = Http::accept('application/json')
            ->contentType('application/json')
            ->withHeader('Authorization', 'Bearer '.$this->accessToken)
            ->get("https://osu.ppy.sh/api/v2/rooms/$roomId")
            ->json();

        $this->lastRequestTimestamp = microtime(true);

        return $response;
    }

    public function getRoomLeaderboard(int $roomId, int $page = 1)
    {
        $this->ensureCredentials();
        $this->ensureCooldown();

        $response = Http::accept('application/json')
            ->contentType('application/json')
            ->withHeader('Authorization', 'Bearer '.$this->accessToken)
            ->get("https://osu.ppy.sh/api/v2/rooms/$roomId/leaderboard", ['page' => $page])
            ->json();

        $this->lastRequestTimestamp = microtime(true);

        return $response;
    }
}
