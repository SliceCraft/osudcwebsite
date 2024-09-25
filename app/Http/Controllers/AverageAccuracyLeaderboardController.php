<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AverageAccuracyLeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $data["players_running_in_job"] = DB::table('jobs')->whereLike('payload', '%RecalculatePlayerData%')->count();
        $data["placements_running_in_job"] = DB::table('jobs')->whereLike('payload', '%CalculatePlacement%')->count();
        $data["leaderboard"] = Player::orderBy('average_accuracy', 'DESC')->where('current_streak', '=', Score::max('daily_challenge') + 1)->paginate(50);
        for($i = 0; $i < count($data["leaderboard"]); $i++){
            $data["leaderboard"][$i]->placement = ($data["leaderboard"]->perPage() * ($data["leaderboard"]->currentPage() - 1)) + $i + 1;
        }
        return view('average_acc_leaderboard', $data);
    }
}
