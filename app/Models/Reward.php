<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rank',
        'amount',
        'rank2',
        'amount2',
        'rank3',
        'amount3',
        'status',
    ];

    public function userDetails()
    {
        return $this->belongsTo(User::class, 'user_id');//, 'id', 'user_id'
    }
}
