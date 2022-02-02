<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoiIncome extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'income_type',
        'amount',
        'status',
        'start_date',
        'end_date',
        'count',
        'pay_date',
    ];
}
