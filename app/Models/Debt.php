<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Debt extends Model
{
    // Guard the id field
    protected $guarded = ['id'];

    // Define the relationship
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
