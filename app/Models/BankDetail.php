<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bank;

class BankDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'holder_name',
        'bank_id',
        'name',
        'branch',
        'ifsc',
        'account_no',
        'account_type',
    ];

    public function banks()
    {
        return $this->belongsTo(Bank::class,'bank_id');
    }
}
