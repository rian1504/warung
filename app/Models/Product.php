<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Guard the id field
    protected $guarded = ['id'];
}
