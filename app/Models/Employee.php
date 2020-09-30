<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Employee extends Model
{
    public $primaryKey = 'uuid';

    public $incrementing = false;

    public $fillable = [
        'name',
        'department',
        'position',
        'address',
        'contact',
        'email',
        'access_code',
        'checkIn',
        'checkOut'
    ];

    public static function boot()
    {
    parent::boot();
    self::creating(function ($model) {
        $model->uuid = (string) Uuid::generate(4);
        });
    }   

}