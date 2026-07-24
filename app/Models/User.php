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

    /**
     * Cek apakah pengguna adalah Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Cek apakah pengguna adalah Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah pengguna adalah Operator.
     */
    public function isOperator(): bool
    {
        return $this->role === 'operator';
    }

    /**
     * Cek apakah pengguna dapat menghapus data.
     * Super Admin dan Admin dapat menghapus, Operator tidak.
     */
    public function canDelete(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * Mendapatkan label role yang ramah untuk ditampilkan.
     */
    public function roleName(): string
    {
        return match ($this->role) {
            'super_admin' => 'Super Admin',
            'admin'       => 'Admin',
            'operator'    => 'Operator',
            default       => ucfirst($this->role),
        };
    }
}
