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
        Player::query()->chunk(100, function ($players) {
            RecalculatePlayerData::dispatch($players->pluck('user_id')->toArray());
        });
    }
}
