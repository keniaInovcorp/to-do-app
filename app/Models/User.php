<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

/**
 * User Model
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 */
class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
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
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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
     * Get the relationship with the user's tasks.
     *
     * A user can have many tasks.
     *
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the initial color gradient classes for the user's avatar.
     *
     * Returns consistent gradient colors based on the first letter of the name.
     *
     * @return string Tailwind CSS gradient classes.
     */
    public function getInitialColorAttribute(): string
    {
        // Using solid dark colors instead of gradients for better contrast
        $colors = [
            'A' => 'bg-blue-800',
            'B' => 'bg-purple-800',
            'C' => 'bg-green-800',
            'D' => 'bg-yellow-800',
            'E' => 'bg-red-800',
            'F' => 'bg-indigo-800',
            'G' => 'bg-teal-800',
            'H' => 'bg-pink-800',
            'I' => 'bg-violet-800',
            'J' => 'bg-amber-800',
            'K' => 'bg-blue-900',
            'L' => 'bg-green-900',
            'M' => 'bg-purple-900',
            'N' => 'bg-red-900',
            'O' => 'bg-orange-800',
            'P' => 'bg-cyan-800',
            'Q' => 'bg-emerald-800',
            'R' => 'bg-rose-800',
            'S' => 'bg-indigo-900',
            'T' => 'bg-teal-900',
            'U' => 'bg-violet-900',
            'V' => 'bg-pink-900',
            'W' => 'bg-blue-800',
            'X' => 'bg-green-800',
            'Y' => 'bg-yellow-800',
            'Z' => 'bg-purple-800',
        ];

        $initial = strtoupper(substr($this->name, 0, 1));
        return $colors[$initial] ?? 'bg-gray-800';
    }
}
