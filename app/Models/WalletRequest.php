<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class WalletRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'trans_id',
        'screenshot',
        'status',
        'reason',
    ];

    public function wrUsers()
    {
        return $this->belongsTo(User::class, 'user_id');//, 'id', 'user_id'
    }
}
