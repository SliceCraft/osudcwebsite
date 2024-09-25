<?php

namespace App\Console\Commands;

use App\Jobs\CalculatePlacement;
use App\Jobs\RecalculatePlayerData;
use App\Models\Player;
use App\Models\Score;
use Illuminate\Console\Command;

class QueuePlacementCalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:queue-placement-calculation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue the recalculation of all placements';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $score_count = Score::max('id');
        for($i = 0; $i < $score_count; $i += 10000){
            $scores = Score::query()->where('id', '>=', $i)->where('id', '<', $i + 10000)->where('placement', '=', 0)->get();
            foreach ($scores as $score){
                CalculatePlacement::dispatch($score->id);
            }
        }
    }
}
