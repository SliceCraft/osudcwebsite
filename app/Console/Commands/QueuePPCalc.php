<?php

namespace App\Console\Commands;

use App\Jobs\CalculatePlacement;
use App\Jobs\CalculatePPJob;
use App\Jobs\RecalculatePlayerData;
use App\Models\DailyChallenge;
use App\Models\Player;
use App\Models\Score;
use Illuminate\Console\Command;

class QueuePPCalc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:queue-pp-calc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue the pp recalculation the first 100k scores that require pp recalculation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Score::query()
            ->where(function ($builder) {
                $builder->where('pp', '=', -1)
                    ->orWhere('pp_version', '<', config('app.osu_pp.version'))
                    ->orWhereNull('pp_version');
            })
            ->limit(100000)
            ->chunkById(50, function ($scores) {
            $dailyChallenges = $scores->pluck('daily_challenge')->unique();

            foreach ($dailyChallenges as $dailyChallengeId) {
                $dailyChallenge = DailyChallenge::query()->where('id', '=', $dailyChallengeId)->first();
                $dailyChallengeScores = $scores->where('daily_challenge', '=', $dailyChallenge->id);

                $this->info("Queuing {$dailyChallengeScores->count()} scores for daily challenge $dailyChallengeId for pp calculation");

                $unserialisedScores = [];

                /**
                 * @var Score $score
                 */
                foreach ($dailyChallengeScores as $score) {
                    $unserialisedScores[] = $score->getAttributes();
                }

                $unserialisedScores = collect($unserialisedScores);

                dispatch(new CalculatePPJob($unserialisedScores, $dailyChallenge->beatmap_id, $dailyChallenge->ruleset_id))->onQueue('pp');
            }
        });
    }
}
