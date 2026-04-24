<?php

namespace App\Console\Commands;

use App\Jobs\CalculatePPJob;
use App\Libraries\OsuAPI\OsuAPIClient;
use App\Models\DailyChallenge;
use App\Models\Player;
use App\Models\Score;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GetDailyChallengeLeaderboardData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailychallenge:get-leaderboard-data {--no-pp-schedule}';

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
        $highestDailyChallenge = DailyChallenge::query()->max('id');

        foreach ($dailyChallenges as $dailyChallenge) {
            $this->info("[$dailyChallenge->id/$highestDailyChallenge] Getting information about room $dailyChallenge->room_id");
            $leaderboardData = $osuAPIClient->getRoom($dailyChallenge->room_id);

            if($leaderboardData['active'] == true) {
                $this->info("Room $dailyChallenge->room_id is still active, skipping...");
                continue;
            }

            $playlistId = $leaderboardData['playlist'][0]['id'];

            // This is wrong, the participant count also includes players that attempted but didn't set a play
            // Instead to fix this we fetch the room data and then update the amount of pages later on
            $pages = ceil($leaderboardData['participant_count'] / $this->amountPerPage);

            // Used for pagination of osu api requests
            $cursorString = null;

            for ($page = 1; $page <= $pages; $page++) {
                $this->info("[$dailyChallenge->id/$highestDailyChallenge | $page/$pages] Getting leaderboard data for room $dailyChallenge->room_id");

                $leaderboardData = $osuAPIClient->getRoomPlaylist($dailyChallenge->room_id, $playlistId, $cursorString);
                $attemptsData = $osuAPIClient->getRoomLeaderboard($dailyChallenge->room_id, $page);
                $cursorString = $leaderboardData['cursor_string'];

                // Correct the page count to an accurate number instead of the estimation
                $pages = ceil($leaderboardData['total'] / $this->amountPerPage);

                $this->importScores($leaderboardData, $attemptsData, $dailyChallenge, $page);
            }
        }
    }

    protected function importScores($data, $attemptsData, $dailyChallenge, $page)
    {
        $scoreData = [];
        $playerData = [];

        for($i = 0; $i < count($data['scores']); $i++) {
            $row = $data['scores'][$i];
            $attemptsRow = $attemptsData['leaderboard'][$i];

            // If the user ids don't match something has gone wrong so lets just display -1 to signify something failed
            $attempts = $attemptsRow['user']['id'] == $row['user']['id'] ? $attemptsRow['attempts'] : -1;

            $playerData[] = [
                'user_id' => $row['user']['id'],
                'username' => $row['user']['username'],
            ];

            $scoreData[] = [
                'user_id' => $row['user']['id'],
                'daily_challenge' => $dailyChallenge->id,
                'score' => $row['total_score'],
                'accuracy' => $row['accuracy'] * 100,
                'attempts' => $attempts,
                // pp is updated later, a -1 value signifies the score hasn't been fully processed yet
                'pp' => -1,
                'score_id' => $row['solo_score_id'],
                'placement' => $this->amountPerPage * ($page - 1) + $i + 1,
                'mehs' => $row['statistics']['meh'] ?? 0,
                'goods' => $row['statistics']['ok'] ?? 0,
                'combo' => $row['statistics']['great'] ?? 0,
                'large_tick_misses' => ($row['maximum_statistics']['large_tick_hit'] ?? 0) - ($row['statistics']['large_tick_hit'] ?? 0),
                'slider_tail_misses' => ($row['maximum_statistics']['slider_tail_hit'] ?? 0) - ($row['statistics']['slider_tail_hit'] ?? 0),
                'mods' => json_encode($row['mods']),
                'misses' => $row['statistics']['miss'] ?? 0
            ];
        }

        Player::upsert($playerData, uniqueBy: ['user_id'], update: ['username']);
        Score::insert($scoreData);

        $scoreIds = collect($scoreData)->pluck('score_id');
        $this->queuePPCalc($scoreIds, $dailyChallenge);
    }

    protected function queuePPCalc(Collection $scoreIds, DailyChallenge $dailyChallenge)
    {
        $scores = Score::query()->whereIn('score_id', $scoreIds)->get();
        $unserialisedScores = [];

        /**
         * @var Score $score
         */
        foreach ($scores as $score) {
            $unserialisedScores[] = $score->getAttributes();
        }

        $unserialisedScores = collect($unserialisedScores);

        if (!$this->option('no-pp-schedule')){
            dispatch(new CalculatePPJob($unserialisedScores, $dailyChallenge->beatmap_id, $dailyChallenge->ruleset_id))->onQueue('pp');
        }
    }
}
