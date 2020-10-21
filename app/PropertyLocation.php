<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyLocation extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'district',
        'city',
        'street',
        'latitude',
        'longitude',
        'property_id',
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
     * Get the property that owns the location.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
