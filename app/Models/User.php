<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
     * Determine if the user has the 'admin' role.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Determine if the user has the 'wali_santri' role.
     */
    public function isWaliSantri(): bool
    {
        return $this->role === 'wali_santri';
    }

    /**
     * Determine if the user has the 'umum' role.
     */
    public function isUmum(): bool
    {
        return $this->role === 'umum';
    }

    /**
     * Get the registrations for the user.
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the santris that this user is wali (guardian) of.
     */
    public function santris()
    {
        return $this->hasMany(Santri::class, 'wali_id');
    }

    /**
     * Get kas transactions created by this user.
     */
    public function kasTransactions()
    {
        return $this->hasMany(KasTransaction::class, 'created_by');
    }

    /**
     * Get donations created by this user.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class, 'created_by');
    }
}
