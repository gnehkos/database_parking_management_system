<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    protected $table = 'staff';
    protected $primaryKey = 'staff_id';

    protected $fillable = [
        'username', 'password_hash', 'full_name', 'gender', 'role',
        'phone_number', 'status', 'date_of_birth', 'profile_image',
    ];

    protected $hidden = ['password_hash'];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function getAuthPasswordName()
{
    return 'password_hash';
}

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'staff_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'staff_id');
    }
}
