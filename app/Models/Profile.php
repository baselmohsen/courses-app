<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role',
        'phone',
        'address',
        'bio',
        'avatar',
        'date_of_birth',
        'social_links'
    ];

    protected $casts = [
        'social_links' => 'array',
        'date_of_birth' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
