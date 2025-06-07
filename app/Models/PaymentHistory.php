<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentHistory extends Model
{
    // Guard the id field
    protected $guarded = ['id'];

    // Define the relationship
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
