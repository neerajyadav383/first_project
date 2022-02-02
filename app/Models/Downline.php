<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Downline extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'downline_id',
        'placement',
        'join_amt',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id', 'downline_id');
    }

    public function userDetails()
    {
        return $this->belongsTo(User::class, 'downline_id');//, 'id', 'user_id'
    }
}
