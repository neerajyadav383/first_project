<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'referenceId',
        'bankAccount',
        'ifsc',
        'beneId',
        'amount',
        'status',
        'utr',
        'addedOn',
        'processedOn',
        'transferMode',
        'acknowledged',
        'tds',
    ];

    public function userDetails()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
