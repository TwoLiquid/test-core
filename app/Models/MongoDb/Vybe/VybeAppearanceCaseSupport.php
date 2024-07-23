<?php

namespace App\Models\MongoDb\Vybe;

use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Place\CityPlace;
use App\Models\MySql\Place\CountryPlace;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\HybridRelations;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\Vybe\VybeAppearanceCaseSupport
 *
 * @property string $_id
 * @property int $appearance_case_id
 * @property string $unit_suggestion
 * @property string $city_place_id
 * @property array $platforms_ids
 * @property-read AppearanceCase $appearanceCase
 * @property-read CountryPlace $countryPlace
 * @method static Builder|VybeAppearanceCaseSupport find(string $id)
 * @method static Builder|VybeAppearanceCaseSupport query()
 */
class VybeAppearanceCaseSupport extends Model
{
    use HybridRelations;

    /**
     * Database connection type
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * Database collection name
     *
     * @var string
     */
    protected $collection = 'vybe_appearance_case_supports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appearance_case_id', 'unit_suggestion', 'city_place_id', 'platforms_ids'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'platforms_ids' => 'array'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function appearanceCase() : BelongsTo
    {
        return $this->belongsTo(AppearanceCase::class);
    }

    /**
     * @return BelongsTo
     */
    public function cityPlace() : BelongsTo
    {
        return $this->belongsTo(
            CityPlace::class,
            'city_place_id',
            'place_id'
        );
    }
}
