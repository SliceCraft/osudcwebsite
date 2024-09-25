<?php

namespace App\Jobs;

use App\Models\Score;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class CalculatePlacement implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $id)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $score = Score::find($this->id);
        $scores = Score::query()->where('daily_challenge', '=', $score->daily_challenge)->get();
        for($i = 0; $i < count($scores); $i++){
            if($scores[$i]->id != $this->id) continue;
            $score->placement = $i + 1;
            break;
        }
        $score->save();
    }
}
