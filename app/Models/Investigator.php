<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investigator extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge_number',
        'full_name',
        'email',
        'password',
        'profile_image',
        'status',
    ];
}
