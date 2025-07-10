<?php

namespace App\Console\Commands;

use App\Libraries\OsuAPI\OsuAPIClient;
use App\Models\DailyChallenge;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class GetDailyChallengeRoomData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // TODO: Implement room type
    protected $signature = 'dailychallenge:get-room-data {--room-type=} {--unattended}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the room data for the current active daily challenge room';

    /**
     * Execute the console command.
     */
    public function handle(OsuAPIClient $osuAPIClient): void
    {
        $existingDailyChallengeForToday = DailyChallenge::query()->where('ends_at', '>', now())->count();

        if ($existingDailyChallengeForToday > 0) {
            if ($this->option('unattended')){
                $this->info('Daily challenge room id already obtained.');
                $this->info('Exiting...');
                return;
            }else if(!$this->confirm('There is already a daily challenge room collected for today, are you sure you want to query for new rooms?')){
                $this->info('Exiting...');
                return;
            }
        }

        $rooms = $osuAPIClient->getRooms();
        $dailyChallengeRooms = Arr::where($rooms, function ($item) {
            return $item['category'] === 'daily_challenge';
        });
        $sortedDailyChallengeRooms = Arr::sort($dailyChallengeRooms, function ($item) {
            return $item['ends_at'];
        });

        $dailyChallengeRoomIds = Arr::pluck($sortedDailyChallengeRooms, 'id');

        $this->info('Removing existing daily challenge room ids if they exist');
        DailyChallenge::query()->whereIn('room_id', $dailyChallengeRoomIds)->delete();

        foreach ($sortedDailyChallengeRooms as $dailyChallengeRoomRoom) {
            $this->info("Adding room {$dailyChallengeRoomRoom['name']}");

            $dailyChallengeRow = new DailyChallenge();
            $dailyChallengeRow->id = DailyChallenge::count() + 1;
            $dailyChallengeRow->room_id = $dailyChallengeRoomRoom['id'];
            $dailyChallengeRow->starts_at = $dailyChallengeRoomRoom['starts_at'];
            $dailyChallengeRow->ends_at = $dailyChallengeRoomRoom['ends_at'];
            $dailyChallengeRow->save();
        }

        $this->info('Daily challenge room data successfully added');
    }
}
