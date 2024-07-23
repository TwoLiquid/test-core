<?php

namespace App\Models\MySql;

use App\Models\MySql\Place\CountryPlace;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Eloquent;

/**
 * App\Models\MySql\PhoneCode
 *
 * @property int $id
 * @property string $country_place_id
 * @property string $code
 * @property bool $is_default
 * @property-read CountryPlace $countryPlace
 * @method static Builder|PhoneCode newModelQuery()
 * @method static Builder|PhoneCode newQuery()
 * @method static Builder|PhoneCode query()
 * @method static Builder|PhoneCode whereCode($value)
 * @method static Builder|PhoneCode whereCountryPlaceId($value)
 * @method static Builder|PhoneCode whereId($value)
 * @method static Builder|PhoneCode whereIsDefault($value)
 * @mixin Eloquent
 */
class PhoneCode extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'phone_codes';

    /**
     * Provide default timestamps usage
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_place_id', 'code', 'is_default'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_default' => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function countryPlace() : BelongsTo
    {
        return $this->belongsTo(
            CountryPlace::class,
            'country_place_id',
            'place_id'
        );
    }
}
