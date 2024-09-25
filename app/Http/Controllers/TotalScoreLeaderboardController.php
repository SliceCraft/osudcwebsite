<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TotalScoreLeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $data["players_running_in_job"] = DB::table('jobs')->whereLike('payload', '%RecalculatePlayerData%')->count();
        $data["placements_running_in_job"] = DB::table('jobs')->whereLike('payload', '%CalculatePlacement%')->count();
        $data["leaderboard"] = Player::orderBy('total_score', 'DESC')->paginate(50);
        for($i = 0; $i < count($data["leaderboard"]); $i++){
            $data["leaderboard"][$i]->placement = ($data["leaderboard"]->perPage() * ($data["leaderboard"]->currentPage() - 1)) + $i + 1;
        }
        return view('total_score_leaderboard', $data);
    }
}
