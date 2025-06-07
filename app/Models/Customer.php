<?php

namespace App\Models;

use App\Models\Debt;
use App\Models\PaymentHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    // Guard the id field
    protected $guarded = ['id'];

    public function debts(): HasMany
    {
        return $this->hasMany(Debt::class);
    }

    public function payment_histories(): HasMany
    {
        return $this->hasMany(PaymentHistory::class);
    }
}
