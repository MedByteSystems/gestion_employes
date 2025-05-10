<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function employe()
    {
        return $this->hasOne(Employe::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }
    protected $appends = ['avatar'];

public function getAvatarAttribute()
{
    return $this->employe->photo ?? 'images/default-avatar.png';
}


}