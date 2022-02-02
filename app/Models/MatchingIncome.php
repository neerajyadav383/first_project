<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchingIncome extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'left',
        'right',
        'left2',
        'right2',
        'pair',
    ];
}
