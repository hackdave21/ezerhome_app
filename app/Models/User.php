<?php

namespace App\Models;

use App\Enums\DeactivationReason;
use App\Enums\UserStatus;
use App\Support\Phone;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, TwoFactorAuthenticatable;

    /** Rôles pour lesquels la double authentification est obligatoire (cahier des charges §4.2). */
    public const TWO_FACTOR_ROLES = ['therapeute', 'formateur_coach', 'comite', 'admin'];

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'status', 'locale', 'preferred_contact',
        'email_verified_at', 'phone_verified_at', 'password_changed_at',
        'deactivated_at', 'deactivation_reason', 'reactivated_at', 'last_activity_at',
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
            'deactivation_reason' => DeactivationReason::class,
            'deactivated_at' => 'datetime',
            'reactivated_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /** Retrouve un compte par e-mail ou par téléphone (+228 facultatif). */
    public static function findByIdentifier(string $identifier): ?self
    {
        $identifier = trim($identifier);

        if (Phone::isIdentifierEmail($identifier)) {
            return static::where('email', mb_strtolower($identifier))->first();
        }

        $phone = Phone::normalize($identifier);

        return $phone ? static::where('phone', $phone)->first() : null;
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::Active;
    }

    public function hasConfirmedTwoFactor(): bool
    {
        return $this->two_factor_secret !== null && $this->two_factor_confirmed_at !== null;
    }

    public function mustUseTwoFactor(): bool
    {
        return $this->hasAnyRole(self::TWO_FACTOR_ROLES);
    }

    /** Nombre d'appareils connectés (sessions en base). */
    public function activeSessionsCount(): int
    {
        if (config('session.driver') !== 'database') {
            return 1;
        }

        return DB::table(config('session.table', 'sessions'))->where('user_id', $this->getKey())->count();
    }

    /** Ferme toutes les sessions de ce compte, sauf celle indiquée. */
    public function logoutEverywhereElse(?string $keepSessionId = null): void
    {
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $this->getKey())
                ->when($keepSessionId, fn ($q) => $q->where('id', '!=', $keepSessionId))
                ->delete();
        }

        $this->forceFill(['remember_token' => Str::random(60)])->save();
    }
}
