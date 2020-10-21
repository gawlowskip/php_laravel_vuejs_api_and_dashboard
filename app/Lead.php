<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ad_id',
        'user_id',
        'full_name',
        'email',
        'clicked_on',
        'latitude',
        'longitude'
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
        'pivot'
    ];

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
     * Get the ad for the lead.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ad()
    {
        return $this->hasOne(Ad::class, 'id', 'ad_id');
    }

    /**
     * Get the user for the lead.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
