<?php

namespace App;

use App\Scopes\DeveloperScope;

class Developer extends User
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new DeveloperScope);
    }

    /**
     * Custom implementation default find() method
     *
     * @param $value
     * @return mixed
     */
    public static function find($value)
    {
        $developer = new Developer();
        return $developer->where('id', $value)->orWhere('email', $value)->orWhere('facebook_id', $value)->first();
    }

    /**
     * Custom implementation default findOrFail() method
     *
     * @param $value
     * @return mixed
     */
    public static function findOrFail($value)
    {
        $developer = new Developer();
        return $developer->where('id', $value)->orWhere('email', $value)->orWhere('facebook_id', $value)->firstOrFail();
    }
}
