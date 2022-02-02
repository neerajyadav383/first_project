<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class RenewalReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'renewal_amt',
        'status',
    ];

    public function rrUsers()
    {
        return $this->belongsTo(User::class, 'user_id');//, 'id', 'user_id'
    }
    
}
