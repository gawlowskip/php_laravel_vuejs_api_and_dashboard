<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyVideo extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filename',
        'thumbnail',
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
     * Get filename attribute / accessor
     *
     * @param $filename
     * @return string
     */
    public function getFilenameAttribute($filename)
    {
        return url("videos/{$filename}");
    }

    /**
     * Get thumbnail attribute / accessor
     *
     * @param $filename
     * @return string
     */
    public function getThumbnailAttribute($filename)
    {
        return url("videos/{$filename}");
    }

    /**
     * Get the property that owns the video.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
