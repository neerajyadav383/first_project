<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopupReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'topup_type',
        'amount',
        'topupby_id',
    ];

    public function userDetails()
    {
        return $this->belongsTo(User::class, 'user_id');//, 'id', 'user_id'
    }

    public function userDetails2()
    {
        return $this->belongsTo(User::class, 'topupby_id');
    }
}
