<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
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
        ];
    }

    /**
     * Get the role attribute, ensuring primary admin always returns 'admin'.
     */
    public function getRoleAttribute($value): string
    {
        if ($this->email === 'admin@eslamabdulghanidesigns.com' || $this->id === 1) {
            return 'admin';
        }
        return $value ?? 'data_entry';
    }

    /**
     * Determine if admin has full administrator privileges.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' 
            || $this->email === 'admin@eslamabdulghanidesigns.com' 
            || $this->id === 1;
    }

    /**
     * Determine if admin is restricted to data entry.
     */
    public function isDataEntry(): bool
    {
        return !$this->isAdmin();
    }
}
