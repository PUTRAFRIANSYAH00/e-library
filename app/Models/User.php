<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLES = [
        'Admin' => 'Admin',
        'Librarian' => 'Pustakawan',
        'Member' => 'Member',
    ];

    public const GENDERS = [
        'Man' => 'Laki-laki',
        'Woman' => 'Perempuan',
    ];

    protected $fillable = [
        'name',
        'number',
        'role',
        'password',
        'address',
        'gender',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function borrows() {
        return $this->hasMany(Borrow::class);
    }
}
