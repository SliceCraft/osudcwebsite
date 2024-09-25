<?php

namespace App\Jobs;

use App\Models\Player;
use App\Models\Score;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RecalculatePlayerData implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $player_id)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $scores = Score::query()->where('user_id', '=', $this->player_id)->get();
        $player = Player::query()->where('user_id', '=', $this->player_id)->first();
        $player->completed_daily_challenges = count($scores);

        $player->total_score = 0;
        $player->total_attempts = 0;
        $player->current_streak = 0;
        $totalacc = 0.0;
        $totalplacement = 0;
        $last_daily_challenge = -2;
        foreach($scores as $score){
            $totalacc += $score->accuracy;
            $player->total_attempts += $score->attempts;
            $player->current_streak = $score->daily_challenge == $last_daily_challenge + 1 ? $player->current_streak + 1 : 1;
            $last_daily_challenge = $score->daily_challenge;
            $player->total_score += $score->score;
            $totalplacement += $score->placement;
        }
        $player->average_accuracy = $totalacc / count($scores);
        $player->average_placement = $totalplacement / count($scores);
        if($last_daily_challenge != Score::max('daily_challenge')){
            $player->current_streak = 0;
        }

        $player->save();
    }
}
