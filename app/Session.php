<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    const TYPE_WEB = 'web';
    const TYPE_API = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'type',
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
    ];
}
