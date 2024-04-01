<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    protected $dateFormat = 'Ymd H:i:s.v';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'email_verified_at', 'password', 'infor',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     *
     */
    public function unemploymentRecords(): HasMany
    {
        return $this->hasMany(UnemploymentRecord::class, 'user_id');
    }

    /**
     *
     */
    public function scrapRecords(): HasMany
    {
        return $this->hasMany(ScrapRecord::class, 'user_id');
    }

    /**
     *
     */
    public function madeCycleInventories(): HasMany
    {
        return $this->hasMany(CycleInventory::class, 'user_made_id');
    }

    /**
     *
     */
    public function validatingCycleInventories(): HasMany
    {
        return $this->hasMany(CycleInventory::class, 'user_validating_id');
    }
}
