<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'description',
    ];
}
