<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Billable, Notifiable, SoftDeletes;

    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';
    const IS_ADMIN = true;
    const ACTIVE = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'architect_name',
        'verified',
        'password',
        'facebook_id',
        'street_1',
        'street_2',
        'city',
        'postal_code',
        'latitude',
        'longitude',
        'cvr_number',
        'active'
    ];

    protected $dates = [
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'verified',
        'password',
        'remember_token',
        'pivot'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_developer',
        'is_active_developer'
    ];

    /**
     * Set name attribute / mutator
     *
     * @param $name
     */
    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name);
    }

    /**
     * Get name attribute / accessor
     *
     * @param $name
     * @return string
     */
    public function getNameAttribute($name)
    {
        return ucwords($name);
    }

    /**
     * Set last name attribute / mutator
     *
     * @param $name
     */
    public function setLastNameAttribute($name)
    {
        $this->attributes['last_name'] = strtolower($name);
    }

    /**
     * Get last name attribute / accessor
     *
     * @param $name
     * @return string
     */
    public function getLastNameAttribute($name)
    {
        return ucwords($name);
    }

    /**
     * Set email attribute / mutator
     *
     * @param $email
     */
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    /**
     * Get latitude attribute / accessor
     *
     * @param $latitude
     * @return string
     */
    public function getLatitudeAttribute($latitude)
    {
        return (float)$latitude;
    }

    /**
     * Get longitude attribute / accessor
     *
     * @param $longitude
     * @return string
     */
    public function getLongitudeAttribute($longitude)
    {
        return (float)$longitude;
    }

    /**
     * Set active attribute / mutator
     *
     * @param $active
     */
    public function setActiveAttribute($active)
    {
        $this->attributes['active'] = intval($active);
    }

    /**
     * Get is_developer attribute / accessor
     *
     * @return bool
     */
    public function getIsDeveloperAttribute()
    {
        $isDeveloper = 0;

        $developer = Developer::find($this->id);
        if (!empty($developer) && $developer instanceof Developer) {
            $isDeveloper = 1;
        }

        return (bool)$isDeveloper;
    }

    /**
     * Get is_active_developer attribute / accessor
     *
     * @return bool
     */
    public function getIsActiveDeveloperAttribute()
    {
        return (bool)$this->activeAgreements()->exists();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Set user as verified user
     *
     * @return bool
     */
    public function isVerified()
    {
        return $this->verified == User::VERIFIED_USER;
    }

    /**
     * Custom implementation default find() method
     *
     * @param $value
     * @return mixed
     */
    public static function find($value)
    {
        $user = new User();
        return $user->where('id', $value)->orWhere('email', $value)->orWhere('facebook_id', $value)->first();
    }

    /**
     * Custom implementation default findOrFail() method
     *
     * @param $value
     * @return mixed
     */
    public static function findOrFail($value)
    {
        $user = new User();
        return $user->where('id', $value)->orWhere('email', $value)->orWhere('facebook_id', $value)->firstOrFail();
    }

    /**
     * Get the ads for the User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ads()
    {
        return $this->hasMany(Ad::class, 'developer_id');
    }

    /**
     * Get the active agreements for the developer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activeAgreements()
    {
        $now = Carbon::now()->toDateTimeString();
        return $this->hasMany(Agreement::class, 'developer_id', 'id')->where('verified', Agreement::VERIFIED_AGREEMENT)->where(function($query) use ($now) {
            $query->where('agreements.to', '>=', $now);
            $query->orWhere('agreements.trial_ends_at', '>=', $now);
        })->withTrashed();
    }

    /**
     * Get the agreements for the User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function agreements()
    {
        return $this->hasMany(Agreement::class, 'developer_id', 'id')->where('verified', 1);
    }

    /**
     * Get the leads that owns the user.
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * Get the properties for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany(Property::class, 'developer_id');
    }
}
