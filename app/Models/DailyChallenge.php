<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $room_id
 * @property $starts_at
 * @property $ends_at
 */
class DailyChallenge extends Model
{
    public $timestamps = false;
}
