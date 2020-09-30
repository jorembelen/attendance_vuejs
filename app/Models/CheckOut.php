<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckOut extends Model
{
    protected $fillable = [
        'rule_name', 'checkOut_time', 
    ];
}
