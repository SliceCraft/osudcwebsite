<?php

namespace App\Http\Controllers;

use App\Jobs\RecalculatePlayerData;
use App\Models\Player;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $data["player_count"] = Player::count();
        $data["total_lb_entries"] = Score::count();
        $data["total_maxed_dc"] = Player::query()->where('current_streak', '=', Score::max('daily_challenge'))->count();
        $data["total_attempts"] = Player::sum('total_attempts');
        $data["total_score"] = Player::sum('total_score');
        return view('welcome', $data);
    }
}
