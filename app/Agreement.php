<?php

namespace App;

use App\Scopes\AgreementScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agreement extends Model
{
    use SoftDeletes;

    const VERIFIED_AGREEMENT = 1;
    const UNVERIFIED_AGREEMENT = 0;
    const TYPE_REGULAR = 'regular';
    const TYPE_TRIAL = 'trial';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'developer_id',
        'from',
        'to',
        'type',
        'trial_starts_at',
        'trial_ends_at',
        'stripe_plan',
        'stripe_charge',
        'price',
        'currency',
        'verified',
        'status'
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
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new AgreementScope);
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
     * Get verified attribute / accessor
     *
     * @param $verified
     * @return bool
     */
    public function getVerifiedAttribute($verified)
    {
        return (bool)$verified;
    }

    /**
     * Get from_date attribute / accessor
     *
     * @return string
     */
    public function getFromDateAttribute()
    {
        switch ($this->type) {
            case $this::TYPE_REGULAR:
                return (string)$this->from;
            case $this::TYPE_TRIAL:
                return (string)$this->trial_starts_at;
            default:
                return null;
        }
    }

    /**
     * Get to_date attribute / accessor
     *
     * @return string
     */
    public function getToDateAttribute()
    {
        switch ($this->type) {
            case $this::TYPE_REGULAR:
                return (string)$this->to;
            case $this::TYPE_TRIAL:
                return (string)$this->trial_ends_at;
            default:
                return null;
        }
    }
}
