<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyChallenge;
use Illuminate\Http\Request;

class RoomsController extends Controller
{
    public function index(Request $request)
    {
        $dailyChallenges = DailyChallenge::all();

        $rooms = [];

        foreach ($dailyChallenges as $dailyChallenge) {
            $rooms[] = [
                'id' => $dailyChallenge->id,
                'room_id' => $dailyChallenge->room_id
            ];
        }

        return response()->json($rooms);
    }
}
