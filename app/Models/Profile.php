<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'about',
        'phone_number',
        'profile_image',
        'location',
        'education',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
