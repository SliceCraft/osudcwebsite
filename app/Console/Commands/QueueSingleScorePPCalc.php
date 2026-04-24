<?php

namespace App\Console\Commands;

use App\Jobs\CalculatePPJob;
use App\Jobs\RecalculatePlayerData;
use App\Models\DailyChallenge;
use App\Models\Player;
use App\Models\Score;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class QueueSingleScorePPCalc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:queue-single-score-pp-calc {score_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue the PP recalculation of a single score';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->hasArgument('score_id')) {
            $this->error('Missing score id');
            return;
        }

        $score = Score::query()->where('score_id', '=', $this->argument('score_id'))->first();
        $dailyChallenge = DailyChallenge::query()->where('id', '=', $score->daily_challenge)->first();

        $unserialisedScores = collect([$score->getAttributes()]);

        dispatch(new CalculatePPJob($unserialisedScores, $dailyChallenge->beatmap_id, $dailyChallenge->ruleset_id))->onQueue('pp');
        $this->info('Job queued');
    }
}
