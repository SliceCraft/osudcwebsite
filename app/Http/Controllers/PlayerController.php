<?php

namespace App\Http\Controllers;

use App\Jobs\RecalculatePlayerData;
use App\Models\Player;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PlayerController extends Controller
{
    public function index(Request $request, ?string $username = null)
    {
        if($username == null){
            return view('playersearch');
        }

        // This is not the best search logic but should work for most cases
        $player = Player::query()->where('user_id', '=', $username)->orWhereLike('username', "%$username%")->first();
        if($player == null){
            return view('playersearch', [
                "error" => "Unable to find this player"
            ]);
        }
        if($player->user_id != $username){
            return redirect()->route('userinfo', [
                'username' => $player->user_id
            ]);
        }

        $data = [];
        $data['player'] = $player;
        $data['dailychallenges'] = Score::query()->where('user_id', '=', $player->user_id)->orderBy('daily_challenge', 'ASC')->get();
        return view('playerinfo', $data);
    }

    public function post(Request $request)
    {
        if(!isset($request->all()['username'])) {
            return redirect()->route('userinfo');
        }

        return redirect()->route('userinfo', [
            'username' => $request->all()['username']
        ]);
    }
}
