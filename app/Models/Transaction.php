<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'payment_received';

    protected $fillable = [
        'id', 'user_id','transaction_id', 'type', 'amount','tax_in_percentage','tax_amount','total_amount','transaction_status','card_token','bank_account_id','paid_to_bank_account','created_at'
    ];
}
