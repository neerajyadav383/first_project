<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\BankDetail;
use App\Models\State;
use App\Models\City;
use App\Models\Downline;
use App\Models\RenewalReport;
use App\Models\WalletRequest;
use App\Models\ClosingStatement;
use App\Models\RoiIncome;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'userid',
        'sponsor_id',
        'placement_id',
        'placement',
        'name',
        'email',
        'mobile',
        'password',
        'trans_pass',
        'state_id',
        'city_id',
        'bank_id',
        'pincode',
        'address',
        'photo',
        'status',
        'roi',
        'booster',
        'direct',
        'matching',
        'direct_team_matching',
        'reward',
        'wallet',
        'topup_wallet',
        'activation_timestamp',
        'direct_mems',
        'left_direct',
        'right_direct',
        'join_amt',
        'total_earning',
        'earning_date',
        'wallet_lock',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'trans_pass',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];


    public function roles()
    {
        return $this->belongsToMany(Role::class,'users_roles');
    }

    public function hasRole(... $roles ) 
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    public function bank_details()
    {
        return $this->belongsTo(BankDetail::class,'bank_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class,'state_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }

    public function downline()
    {
        return $this->hasMany(Downline::class);
    }

    public function renewalReport()
    {
        return $this->hasMany(RenewalReport::class);
    }

    public function walletRequest()
    {
        return $this->hasMany(WalletRequest::class);
    }

    public function closingStatement()
    {
        return $this->hasMany(ClosingStatement::class);
    }

    public function topupReport()
    {
        return $this->hasMany(TopupReport::class);
    }

    public function roiIncomes()
    {
        return $this->hasMany(RoiIncome::class, 'user_id');
    }

    public function beneficiaryDetails()
    {
        return $this->hasMany(BeneficiaryDetail::class, 'user_id');
    }

    public function payoutDetails()
    {
        return $this->hasMany(PayoutDetail::class, 'user_id');
    }

    public function sponsorDetails()
    {
        return $this->belongsTo(User::class, 'sponsor_id');//, 'id', 'user_id'
    }

    public function placementDetails()
    {
        return $this->belongsTo(User::class, 'sponsor_id');//, 'id', 'user_id'
    }

    public function investment()
    {
        return $this->hasMany(Investment::class);
    }

}
