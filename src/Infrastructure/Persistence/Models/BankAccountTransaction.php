<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccountTransaction extends Model
{
    protected $table = 'bank_account_transaction';
    protected $fillable = ['bank_account_id', 'payee_bank_account_id', 'amount', 'description'];
    public $timestamps = true;
}
