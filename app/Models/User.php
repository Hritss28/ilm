<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'telp',
        'password',
        'role',
        'photo_url',
        'kecamatan',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the news articles written by this user.
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'author_id');
    }

    /**
     * Get the videos created by this user.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class, 'created_by');
    }

    /**
     * Get the galleries created by this user.
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class, 'created_by');
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a redaktur.
     */
    public function isRedaktur(): bool
    {
        return $this->role === 'redaktur';
    }

    /**
     * Check if the user is an author.
     */
    public function isAuthor(): bool
    {
        return $this->role === 'author';
    }
}
