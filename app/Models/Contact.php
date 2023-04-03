<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'number',
        'image',
        'country',
        'city',
        'address',
        'zip',
        'user_id'
    ];

    public function contactUser()
    {
        return $this->belongsTo('App\Models\User');
    }
}
