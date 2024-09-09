<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCompanySetting extends Model
{
    use HasFactory;

    protected $table = 'setting_company';

    protected $fillable = [
        'company_name','company_logo','address','email','phone','website','individual_plan_price','individual_plan_price','service_fee','app_version','update_on_the_app','is_maintenance'
    ];
}
