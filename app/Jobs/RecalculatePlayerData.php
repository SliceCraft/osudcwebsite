<?php

namespace App\Jobs;

use App\Models\DailyChallenge;
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
    public function __construct(public array $playerIds)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $players = Player::query()->whereIn('user_id', $this->playerIds)->get();
        $scores = Score::query()->whereIn('user_id', $this->playerIds)->get();
        $dailyChallenges = Score::max('daily_challenge');

        foreach($players as $player) {
            $playerScores = $scores->where('user_id', $player->user_id)->sortBy('daily_challenge');

            $player->completed_daily_challenges = count($playerScores);

            $totalScore = 0;
            $totalAttempts = 0;
            $currentStreak = 0;
            $totalAcc = 0.0;
            $totalPlacement = 0;
            $lastDailyChallenge = -2;

            foreach($playerScores as $score){
                $totalAcc += $score->accuracy;
                $totalAttempts += $score->attempts;
                $currentStreak = ($score->daily_challenge - 1) == $lastDailyChallenge ? $currentStreak + 1 : 1;
                $lastDailyChallenge = $score->daily_challenge;
                $totalScore += $score->score;
                $totalPlacement += $score->placement;
            }

            if($lastDailyChallenge != $dailyChallenges){
                $currentStreak = 0;
            }

            $player->total_score = $totalScore;
            $player->total_attempts = $totalAttempts;
            $player->current_streak = $currentStreak;
            $player->average_accuracy = $totalAcc / $dailyChallenges;
            // TODO: The totalplacement should use the bottom placement when a player didn't play in a particular daily challenge
            $player->average_placement = $totalPlacement / count($playerScores);

            $player->save();
        }
    }

    public $timeout = 10000;
}
