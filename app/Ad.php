<?php

namespace App;

use App\Scopes\AdScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'developer_id',
        'from_date',
        'to_date',
        'active',
        'image',
        'external_image_url',
        'url',
        'price',
        'price_lead',
        'seconds',
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

        static::addGlobalScope(new AdScope);

        static::deleted(function ($ad) {
            $ad->areas()->detach();
        });
    }

    /**
     * Get active attribute / accessor
     *
     * @param $active
     * @return bool
     */
    public function getActiveAttribute($active)
    {
        return (bool)$active;
    }

    /**
     * Get image attribute / accessor
     *
     * @param $image
     * @return string
     */
    public function getImageAttribute($image)
    {
        return $image ? url("img/ads/{$image}") : null;
    }

    /**
     * Get price attribute / accessor
     *
     * @param $price
     * @return float
     */
    public function getPriceAttribute($price)
    {
        return (float)$price;
    }

    /**
     * Get price lead attribute / accessor
     *
     * @param $price_lead
     * @return float
     */
    public function getPriceLeadAttribute($price_lead)
    {
        return (float)$price_lead;
    }

    /**
     * Get the areas that owns the ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function areas()
    {
        return $this->belongsToMany(Area::class)->withTimestamps();
    }

    /**
     * Get the Developer that owns the Ad.
     */
    public function developer()
    {
        return $this->hasOne(User::class, 'id', 'developer_id');
    }

    /**
     * Get the leads that owns the ad.
     */
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
