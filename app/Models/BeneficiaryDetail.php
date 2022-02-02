<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeneficiaryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'beneId',
        'name',
        'email',
        'phone',
        'bankAccount',
        'ifsc',
        'address1',
        'city',
        'state',
        'pincode',
        'date',
        'time',
    ];

    public function userDetails()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
