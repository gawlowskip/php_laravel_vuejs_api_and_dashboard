<?php

namespace App;

use App\Scopes\PropertyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'editing_hash',
        'bricklayer',
        'carpenter',
        'electrician',
        'vvs',
        'entrepreneur',
        'developer_id',
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
     * Boot method
     */
    public static function boot() {
        parent::boot();

        static::addGlobalScope(new PropertyScope);
    }

    /**
     * Get the developer that owns the property.
     */
    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id', 'id');
    }

    /**
     * Get the location for the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function location()
    {
        return $this->hasOne(PropertyLocation::class);
    }

    /**
     * Get the feature for the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function feature()
    {
        return $this->hasOne(PropertyFeature::class);
    }

    /**
     * Get the images for the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    /**
     * Get the videos for the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function videos()
    {
        return $this->hasMany(PropertyVideo::class);
    }

    /**
     * Count all of the Property's visits
     *
     * @return int
     */
    public function countVisits()
    {
        return $this->morphMany(Visit::class, 'visitable')->count();
    }

    /**
     * Get all of the Property's visits
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function visits()
    {
        return $this->morphMany(Visit::class, 'visitable');
    }
}
