<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;

class Staff extends Authenticatable
{
    use HasApiTokens,Notifiable,HasRoles,softDeletes;
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = [
        'first_name', 'last_name','email', 'password', 'email_verified_at','remember_token', 'phone','profile_image','is_superadmin','country_code','otp','forgot_password_code','notification_status','device_id','auth_token','fcm_token','status','referal_code','dob','gender'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        
      ];

    public function get_roles(){
        $roles = [];
        foreach ($this->getRoleNames() as $key => $role) {
            $roles[$key] = $role;
        }

        return $roles;
    }

    
}
