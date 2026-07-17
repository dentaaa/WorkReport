<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    public const ROLE_ADMIN = 'Admin';
    public const ROLE_MEKANIK = 'Mekanik';
    public const ROLE_FOREMAN = 'Foreman';
    public const ROLE_SUPERVISOR = 'Supervisor';
    public const ROLE_DEPT_HEAD = 'Dept. Head';
    public const ROLE_TRAINER = 'Trainer';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    public function workReports()
    {
        return $this->hasMany(WorkReport::class);
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isMekanik()
    {
        return $this->role === self::ROLE_MEKANIK;
    }

    public function isForeman()
    {
        return $this->role === self::ROLE_FOREMAN;
    }

    public function isSupervisor()
    {
        return $this->role === self::ROLE_SUPERVISOR;
    }

    public function isDeptHead()
    {
        return $this->role === self::ROLE_DEPT_HEAD;
    }

    public function isTrainer()
    {
        return $this->role === self::ROLE_TRAINER;
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
