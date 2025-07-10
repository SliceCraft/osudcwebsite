<?php

namespace App\Console\Commands;

use App\Libraries\OsuAPI\OsuAPIClient;
use App\Models\DailyChallenge;
use App\Models\Player;
use App\Models\Score;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class GetDailyChallengeLeaderboardData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailychallenge:get-leaderboard-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the missing daily challenge leaderboard data';

    protected $amountPerPage = 50;

    /**
     * Execute the console command.
     */
    public function handle(OsuAPIClient $osuAPIClient)
    {
        $collectedRooms = Score::select(DB::raw("DISTINCT daily_challenge AS daily_challenge"))->get()->pluck('daily_challenge')->toArray();
        $dailyChallenges = DailyChallenge::query()->whereNotIn('id', $collectedRooms)->orderBy('id')->get();

        foreach ($dailyChallenges as $dailyChallenge) {
            $this->info("Getting information about room $dailyChallenge->room_id");
            $leaderboardData = $osuAPIClient->getRoom($dailyChallenge->room_id);

            if($leaderboardData['active'] == true) {
                $this->info("Room $dailyChallenge->room_id is still active, skipping...");
                continue;
            }

            $pages = ceil($leaderboardData['participant_count'] / $this->amountPerPage);

            for ($page = 1; $page <= $pages; $page++) {
                $this->info("[$page/$pages] Getting leaderboard data for room $dailyChallenge->room_id");

                $leaderboardData = $osuAPIClient->getRoomLeaderboard($dailyChallenge->room_id, $page);
                $this->importScores($leaderboardData, $dailyChallenge, $page);
            }
        }
    }

    protected function importScores($data, $dailyChallenge, $page)
    {
        $scoreData = [];
        $playerData = [];

        for($i = 0; $i < count($data['leaderboard']); $i++) {
            $row = $data['leaderboard'][$i];

            $playerData[] = [
                'user_id' => $row['user']['id'],
                'username' => $row['user']['username'],
            ];

            $scoreData[] = [
                'user_id' => $row['user_id'],
                'daily_challenge' => $dailyChallenge->id,
                'score' => $row['total_score'],
                'accuracy' => $row['accuracy'] * 100,
                'attempts' => $row['attempts'],
                'placement' => $this->amountPerPage * ($page - 1) + $i + 1
            ];
        }

        // The cheapest way to update all players is to just delete them and readd them at once
        Player::query()->whereIn('user_id', Arr::pluck($playerData, 'user_id'))->delete();
        Player::insert($playerData);
        Score::insert($scoreData);
    }
}
