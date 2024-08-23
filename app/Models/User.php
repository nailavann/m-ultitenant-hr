<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
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
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? asset($value)
                : 'https://ui-avatars.com/api/?name=' . $this->name . '&background=D27F6C&color=fff&length=1&font-size=0.60&bold=false',
        );
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->name . ' ' . $this->surname,
        );
    }


    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_managers', 'user_id', 'manager_id')
            ->with('information', 'information.company')
            ->withPivot('priority');
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'user_id', 'id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'user_id', 'id');
    }

    public function emergencies(): HasMany
    {
        return $this->hasMany(Emergency::class, 'user_id', 'id');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(Education::class, 'user_id', 'id');
    }

    public function information(): HasOne
    {
        return $this->hasOne(UserInformation::class, 'user_id', 'id');
    }

    public function leaveInformation(): HasOne
    {
        return $this->hasOne(LeaveInformation::class, 'user_id', 'id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class, 'user_id', 'id')->latest();
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'likes', 'user_id')
            ->withTimestamps();
    }
}
