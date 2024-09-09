<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CompanySetting extends Model
{
    use HasFactory;
    protected $table = 'setting_company';
    protected $fillable = [
            'company_name','company_logo','address','email','phone','premium_plan_name','premium_plan_price','favi_icon','hostname','username','port','password','no_reply_mail','service_fee'
    ];
}
