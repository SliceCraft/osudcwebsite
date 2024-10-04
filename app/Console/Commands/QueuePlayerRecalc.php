<?php

namespace App\Console\Commands;

use App\Jobs\RecalculatePlayerData;
use App\Models\Player;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class QueuePlayerRecalc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:queue-player-recalc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue the recalculation of all player data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Instead of fetching Player::all the query only fetches 10k at a time to prevent memory errors
        $player_count = Player::max('id');
        for($i = 0; $i < $player_count; $i += 10000){
            $players = Player::query()->where('id', '>=', $i)->where('id', '<', $i + 10000)->get();
            foreach ($players as $player){
                RecalculatePlayerData::dispatch($player->user_id);
            }
        }
    }
}
