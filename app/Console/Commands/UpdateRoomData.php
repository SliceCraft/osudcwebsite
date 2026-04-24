<?php

namespace App\Console\Commands;

use App\Libraries\OsuAPI\OsuAPIClient;
use App\Models\DailyChallenge;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UpdateRoomData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailychallenge:update-room-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch room data from already saved daily challenge rooms';

    /**
     * Execute the console command.
     */
    public function handle(OsuAPIClient $client)
    {
        $dailyChallenges = DailyChallenge::query()->orderBy('id')->get();

        foreach ($dailyChallenges as $dailyChallenge) {
            $this->info("[$dailyChallenge->id/{$dailyChallenges->count()}] Updating room data for room $dailyChallenge->room_id");
            $roomData = $client->getRoom($dailyChallenge->room_id);

            $dailyChallenge->starts_at = Carbon::parse($roomData['starts_at']);
            $dailyChallenge->ends_at = Carbon::parse($roomData['ends_at']);
            $dailyChallenge->ruleset_id = $roomData['playlist'][0]['ruleset_id'];
            $dailyChallenge->beatmap_id = $roomData['playlist'][0]['beatmap_id'];
            $dailyChallenge->save();

            $filepath = "$dailyChallenge->beatmap_id.osu";
            if (!Storage::disk('beatmaps')->exists($filepath)) {
                $this->info("Downloading beatmap $dailyChallenge->beatmap_id");
                $response = Http::get("https://osu.ppy.sh/osu/$dailyChallenge->beatmap_id");
                Storage::disk('beatmaps')->put($filepath, $response->body());
            }
        }
    }
}
