<?php

namespace App\Models\MySql\Place;

use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Timezone\Timezone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Eloquent;

/**
 * App\Models\MySql\Place\CityPlace
 *
 * @property int $id
 * @property int|null $timezone_id
 * @property string $place_id
 * @property array $name
 * @property float|null $latitude
 * @property float|null $longitude
 * @property-read Collection<int, AppearanceCase> $appearanceCases
 * @property-read int|null $appearance_cases_count
 * @property-read Timezone|null $timezone
 * @method static Builder|CityPlace newModelQuery()
 * @method static Builder|CityPlace newQuery()
 * @method static Builder|CityPlace query()
 * @method static Builder|CityPlace whereId($value)
 * @method static Builder|CityPlace whereLatitude($value)
 * @method static Builder|CityPlace whereLocale(string $column, string $locale)
 * @method static Builder|CityPlace whereLocales(string $column, array $locales)
 * @method static Builder|CityPlace whereLongitude($value)
 * @method static Builder|CityPlace whereName($value)
 * @method static Builder|CityPlace wherePlaceId($value)
 * @method static Builder|CityPlace whereTimezoneId($value)
 * @mixin Eloquent
 */
class CityPlace extends Model
{
    use HasTranslations;

    /**
     * Database connection type
     *
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'city_places';

    /**
     * Provide default timestamps usage
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Translation field
     *
     * @var array
     */
    public array $translatable = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'place_id', 'timezone_id', 'name', 'latitude', 'longitude'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function timezone() : BelongsTo
    {
        return $this->belongsTo(Timezone::class);
    }

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function appearanceCases() : HasMany
    {
        return $this->hasMany(AppearanceCase::class);
    }
}
