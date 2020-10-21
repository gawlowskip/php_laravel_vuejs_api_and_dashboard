<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;

    const NAME_JYLLAND = 'Jylland';
    const NAME_FYN = 'Fyn';
    const NAME_SJALLAND = 'Sjælland';
    const TYPE_ISLAND = 'island';
    const TYPE_CITY = 'city';
    const COUNTRY_CODE_DENMARK = 'DK'; /* ISO 3166-2 */
    const LATITUDE_JYLLAND = '55.561047, 55.438028, 55.202704, 54.917996, 54.866877, 54.882724, 54.878155, 55.080461, 55.030518, 55.248819, 55.386694, 55.493207, 55.691512, 55.852781, 55.846279, 55.814659, 55.561158, 55.581770, 55.619447, 55.307797, 55.056354, 55.237667';
    const LONGITUDE_JYLLAND = '8.074692, 8.586336, 8.685031, 8.644947, 9.199353, 9.575806, 10.073133, 9.713088, 9.432120, 9.726774, 9.624540, 9.503226, 9.657556, 9.446947, 9.048588, 8.514979, 8.070850, 8.656935, 9.196181, 8.975077, 9.120361, 9.370507';
    const LATITUDE_FYN = '55.363335, 55.245917, 55.125618, 55.065427, 55.028982, 55.067792, 55.183610, 55.307367, 55.412759, 55.477586, 55.613399, 55.436983, 55.551496, 55.619218, 55.559144, 55.539681, 55.430672, 55.343247, 55.333272, 55.200633, 55.471567, 55.382141';
    const LONGITUDE_FYN = '9.793806, 9.889551, 10.004813, 10.212611, 10.508865, 10.738459, 10.803380, 10.789600, 10.719753, 10.748882, 10.619900, 10.427211, 10.479534, 10.292026, 10.047681, 9.844395, 9.804382, 9.879377, 10.268105, 10.558112, 10.046763, 10.602815';
    const LATITUDE_SJALLAND = '55.370931, 55.201800, 54.992617, 54.959453, 54.789521, 54.612748, 54.646079, 54.835915, 54.958304, 55.113846, 55.330249, 55.485031, 55.597073, 55.777099, 55.765839, 55.973687, 56.008736, 55.741446, 55.515782, 55.466769, 54.758069, 54.841551';
    const LONGITUDE_SJALLAND = '11.098938, 11.253938, 11.878459, 11.183482, 10.975834, 11.462061, 11.811323, 12.159422, 12.543441, 12.182809, 12.448532, 12.200929, 12.364096, 12.090576, 11.757030, 11.777017, 11.280222, 10.872773, 11.085999, 11.739581, 11.441330, 11.940511';
    const FILTER_KILOMETERS = 20;

    /* Area Database Seeder */
    const AREA_QUANTITY = 3;
    const AREA_JYLLAND = [
        'name' => Area::NAME_FYN,
        'type' => Area::TYPE_ISLAND,
        'country_code' => Area::COUNTRY_CODE_DENMARK, /* ISO 3166-2 */
        'latitude' => Area::LATITUDE_FYN,
        'longitude' => Area::LONGITUDE_FYN
    ];
    const AREA_FYN = [
        'name' => Area::NAME_JYLLAND,
        'type' => Area::TYPE_ISLAND,
        'country_code' => Area::COUNTRY_CODE_DENMARK, /* ISO 3166-2 */
        'latitude' => Area::LATITUDE_JYLLAND,
        'longitude' => Area::LONGITUDE_JYLLAND
    ];
    const AREA_SJALLAND = [
        'name' => Area::NAME_SJALLAND,
        'type' => Area::TYPE_ISLAND,
        'country_code' => Area::COUNTRY_CODE_DENMARK, /* ISO 3166-2 */
        'latitude' => Area::LATITUDE_SJALLAND,
        'longitude' => Area::LONGITUDE_SJALLAND
    ];
    const AREAS = [Area::AREA_JYLLAND, Area::AREA_FYN, Area::AREA_SJALLAND];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'country_code', /* ISO 3166-2 */
        'latitude',
        'longitude',
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
        return explode(", ",$latitude);
    }

    /**
     * Get longitude attribute / accessor
     *
     * @param $longitude
     * @return string
     */
    public function getLongitudeAttribute($longitude)
    {
        return explode(", ",$longitude);
    }

    /**
     * Get the ads that owns the area.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ads()
    {
        return $this->belongsToMany(Ad::class)->withTimestamps();
    }
}
