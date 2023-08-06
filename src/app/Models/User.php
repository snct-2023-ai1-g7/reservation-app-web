<?php

namespace App\Models;

use Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['user_id', 'password'];
    protected $hidden = ['remember_token'];
    protected $casts = ['password' => 'hashed'];

    public function regeneratePassword() : string
    {
        $newPassword = Str::random();
        $this->update(['password' => $newPassword]);
        return $newPassword;
    }
}
