<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'income_type',
        'amount',
        'by_id',
        'level',
        'level2',
        'level3',
        'level4',
    ];

    public function userDetails()
    {
        return $this->belongsTo(User::class, 'user_id');//, 'id', 'user_id'
    }

    public function userDetails2()
    {
        return $this->belongsTo(User::class, 'by_id');//, 'id', 'user_id'
    }
}
