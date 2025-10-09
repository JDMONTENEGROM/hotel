<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'avatar',
        'password',
        'random_key',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function getAvatar()
    {
        if (! $this->avatar) {
            return asset('img/default/default-user.jpg');
        }

        return asset('img/user/'.$this->name.'-'.$this->id.'/'.$this->avatar);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function isCustomer()
    {
        return $this->role === 'Customer';
    }

    public function scopeSystemUsers($query)
    {
        return $query->where('role', '!=', 'Customer');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}
