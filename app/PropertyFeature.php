<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyFeature extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property_type',
        'material',
        'completion_date',
        'size',
        'rooms_amount',
        'baths_amount',
        'bedrooms_amount',
        'floors',
        'price',
        'property_id'
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
     * Get the property that owns the feature.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
