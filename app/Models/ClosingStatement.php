<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosingStatement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'roi',
        'booster',
        'direct',
        'matching',
        'direct_team_matching',
        'reward',
        'total_amount',
        'tds',
        'avail_amount',
    ];

    public function userDetails()
    {
        return $this->belongsTo(User::class, 'user_id');//, 'id', 'user_id'
    }

}
