<?php

namespace App\Http\Controllers;

use App\Jobs\RecalculatePlayerData;
use App\Models\Player;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LeaderboardController extends Controller
{
    public function index(Request $request, string $leaderboard)
    {
        $data = [];
        switch ($leaderboard) {
            case 'accuracy':
                $data["leaderboard"] = Player::orderBy('average_accuracy', 'DESC')->paginate(50);
                break;
            case 'placement':
                $data["leaderboard"] = Player::orderBy('average_placement', 'ASC')->where('current_streak', '=', Score::max('daily_challenge'))->paginate(50);
                break;
            case 'attempts':
                $data["leaderboard"] = Player::orderBy('total_attempts', 'DESC')->paginate(50);
                break;
            case 'score':
                $data["leaderboard"] = Player::orderBy('total_score', 'DESC')->paginate(50);
                break;
            default:
                abort(404);
        }

        // Add a placement variable to the results calculated based on the page
        for($i = 0; $i < count($data["leaderboard"]); $i++){
            $data["leaderboard"][$i]->placement = ($data["leaderboard"]->perPage() * ($data["leaderboard"]->currentPage() - 1)) + $i + 1;
        }

        return view('leaderboards.'.$leaderboard, $data);
    }
}
