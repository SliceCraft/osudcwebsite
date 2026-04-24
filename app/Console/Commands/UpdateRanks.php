<?php

namespace App\Console\Commands;

use App\Jobs\RecalculatePlayerData;
use App\Models\Player;
use Illuminate\Console\Command;

class UpdateRanks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailychallenge:update-ranks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rank = 1;
        Player::query()->orderByDesc('weighted_pp')->chunk(100, function ($players) use (&$rank) {
            $playerData = [];

            foreach ($players as $player) {
                $playerData[] = [
                    'user_id' => $player->user_id,
                    'username' => $player->username,
                    'rank' => $rank
                ];
                $rank++;
            }

            Player::upsert($playerData, uniqueBy: ['user_id'], update: ['rank']);
        });
    }
}
