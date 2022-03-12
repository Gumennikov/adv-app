<?php

namespace App\Entity;

use App\Entity\Adverts\Advert\Advert;
use Carbon\Carbon;
use InvalidArgumentException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Self_;

/**
 * Class User
 * @package App\Entity
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property boolean $phone_auth
 * @property boolean $phone_verified
 * @property string $phone_verify_token
 * @property Carbon $phone_verify_token_expire
 * @property string $password
 * @property string $verify_token
 * @property string $status
 * @property string $role
 */

class User extends Authenticatable
{
    use Notifiable;

    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MODERATOR = 'moderator';

    public static function statusesList(): array
    {
        return [
            self::STATUS_WAIT => 'Wait',
            self::STATUS_ACTIVE => 'Active',
        ];
    }

    public static function rolesList(): array
    {
        return [
            self::ROLE_USER => 'User',
            self::ROLE_MODERATOR => 'Moderator',
            self::ROLE_ADMIN => 'Admin',
        ];
    }

    public static function register(string $name, string $email, string $password): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'verify_token' => Str::uuid(),
            'status' => self::STATUS_WAIT,
            'role' => self::ROLE_USER,
        ]);
    }

    public static function createByAdmin(string $name, string $email): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt(Str::random()),
            'status' => self::STATUS_ACTIVE,
            'role' => self::ROLE_USER,
        ]);
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function verify(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already verified.');
        }

        $this->update([
            'status' => self::STATUS_ACTIVE,
            'verify_token' => null,
        ]);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'email', 'password', 'status', 'verify_token', 'role', 'phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'phone_verified' => 'boolean',
        'phone_auth' => 'boolean',
        'phone_verify_token_expire' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function changeRole($role): void
    {
        if (!\in_array($role, [self::ROLE_USER, self::ROLE_ADMIN], true)) {
            throw new InvalidArgumentException('Undefined role "' . $role . '."');
        }
        if ($this->role === $role) {
            throw new \DomainException('Role is already assigned.');
        }

        $this->update(['role' => $role]);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isModerator(): bool
    {
        return $this->role === self::ROLE_MODERATOR;
    }

    public function isPhoneVerified(): bool
    {
        return $this->phone_verified === true;
    }

    public function unverifyPhone(): void
    {
        $this->phone_verified = false;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }

    public function requestPhoneVerification(Carbon $now): string
    {
        if (empty($this->phone)) {
            throw new \DomainException('Phone number is empty.');
        }
        if (!empty($this->phone_verify_token) && $this->phone_verify_token_expire
            && $this->phone_verify_token_expire->gt($now)) {
            throw new \DomainException('Token is already requested.');
        }
        $this->phone_verified = false;
        $this->phone_verify_token = (string)random_int(10000, 99999);
        $this->phone_verify_token_expire = $now->copy()->addSeconds(300);
        $this->saveOrFail();

        return $this->phone_verify_token;
    }

    public function verifyPhone($token, Carbon $now): void
    {
        if ($token !== $this->phone_verify_token) {
            throw new \DomainException('Incorrect verify token.');
        }
        if ($this->phone_verify_token_expire->lt($now)) {
            throw new \DomainException('Token is expired.');
        }
        $this->phone_verified = true;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }

    public function isPhoneAuthEnabled(): bool
    {
        return (bool)$this->phone_auth;
    }

    public function enablePhoneAuth(): void
    {
        if (!empty($this->phone) && !$this->isPhoneVerified()) {
            throw new \DomainException('Phone number is empty.');
        }
        $this->phone_auth = true;
        $this->saveOrFail();
    }

    public function disablePhoneAuth(): void
    {
        $this->phone_auth = false;
        $this->saveOrFail();
    }

    public function hasFilledProfile(): bool
    {
        return !empty($this->name) && !empty($this->last_name) && $this->isPhoneVerified();
    }

    public function favorites()
    {
        return $this->belongsToMany(Advert::class, 'advert_favorites', 'user_id', 'advert_id');
    }

    public function addToFavorites($id): void
    {
        if ($this->hasInFavorites($id)) {
            throw new \DomainException('This advert is already added to favorites.');
        }
        $this->favorites()->attach($id);
    }

    public function removeFromFavorites($id): void
    {
        $this->favorites()->detach($id);
    }

    public function hasInFavorites($id): bool
    {
        return $this->favorites()->where('id', $id)->exists();
    }
}
