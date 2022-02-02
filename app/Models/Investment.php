<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;


    

    public function user_details()
    {
        return $this->belongsTo(User::class, 'user_id');//, 'id', 'user_id'
    }
}
