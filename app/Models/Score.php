<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Score extends Model
{
    public function dailyChallenge(): HasOne
    {
        return $this->hasOne(DailyChallenge::class, 'id', 'daily_challenge');
    }
}
