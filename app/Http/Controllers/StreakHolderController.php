<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Score;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StreakHolderController
{
    public function index(Request $request)
    {
        $players = Player::query()
            ->where('current_streak', '=', Score::max('daily_challenge'))
            // This orWhere is to account for a single user, see https://github.com/SliceCraft/osudcwebsite/issues/3
            ->orWhere('user_id', '=', 12404210)
            ->orderByDesc('total_score')
            ->get();

        return view('streakholders', ['players' => $players]);
    }
}
