<?php

namespace App\Models\MongoDb\Vybe\Request\Change;

use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Vybe\Appearance\VybeAppearanceList;
use App\Lists\Vybe\Appearance\VybeAppearanceListItem;
use App\Models\MongoDb\Suggestion\CsauSuggestion;
use App\Models\MySql\Place\CityPlace;
use App\Models\MySql\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Relations\BelongsTo as MongoBelongsTo;
use MongoDB\Laravel\Eloquent\HybridRelations;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\Vybe\Request\Change\VybeChangeRequestAppearanceCase
 *
 * @property string $_id
 * @property int $vybe_change_request_id
 * @property int $appearance_id
 * @property float $price
 * @property float $previous_price
 * @property int $price_status_id
 * @property int $unit_id
 * @property int $previous_unit_id
 * @property string $unit_suggestion
 * @property int $unit_status_id
 * @property string $description
 * @property string $previous_description
 * @property int $description_status_id
 * @property array $platforms_ids
 * @property array $previous_platforms_ids
 * @property int platforms_status_id
 * @property bool $same_location
 * @property bool $previous_same_location
 * @property string $city_place_id
 * @property string $previous_city_place_id
 * @property int $city_place_status_id
 * @property bool $enabled
 * @property bool $previous_enabled
 * @property int $csau_suggestion_id
 * @property-read VybeChangeRequest $vybeChangeRequest
 * @property-read Unit $unit
 * @property-read Unit $previousUnit
 * @property-read CityPlace $cityPlace
 * @property-read CityPlace $previousCityPlace
 * @property-read CsauSuggestion $csauSuggestion
 * @method static Builder|VybeChangeRequestAppearanceCase find(string $id)
 * @method static Builder|VybeChangeRequestAppearanceCase query()
 */
class VybeChangeRequestAppearanceCase extends Model
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
    protected $collection = 'vybe_change_request_appearance_cases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vybe_change_request_id', 'appearance_id', 'price', 'previous_price', 'price_status_id', 'unit_id',
        'previous_unit_id', 'unit_suggestion', 'unit_status_id', 'description', 'previous_description',
        'description_status_id', 'platforms_ids', 'previous_platforms_ids', 'platforms_status_id', 'same_location',
        'previous_same_location', 'city_place_id', 'previous_city_place_id', 'city_place_status_id',
        'enabled', 'previous_enabled', 'csau_suggestion_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'platforms_ids'          => 'array',
        'previous_platforms_ids' => 'array',
        'same_location'          => 'boolean',
        'previous_same_location' => 'boolean',
        'enabled'                => 'boolean',
        'previous_enabled'       => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return MongoBelongsTo
     */
    public function vybeChangeRequest() : MongoBelongsTo
    {
        return $this->belongsTo(VybeChangeRequest::class);
    }

    /**
     * @return BelongsTo
     */
    public function unit() : BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * @return BelongsTo
     */
    public function previousUnit() : BelongsTo
    {
        return $this->belongsTo(Unit::class);
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

    /**
     * @return BelongsTo
     */
    public function previousCityPlace() : BelongsTo
    {
        return $this->belongsTo(
            CityPlace::class,
            'previous_city_place_id',
            'place_id'
        );
    }

    /**
     * @return MongoBelongsTo
     */
    public function csauSuggestion() : MongoBelongsTo
    {
        return $this->belongsTo(CsauSuggestion::class);
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return VybeAppearanceListItem|null
     */
    public function getAppearance() : ?VybeAppearanceListItem
    {
        return VybeAppearanceList::getItem(
            $this->appearance_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getPriceStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->price_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getUnitStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->unit_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getDescriptionStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->description_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getPlatformsStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->platforms_status_id
        );
    }

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getCityPlaceStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->city_place_status_id
        );
    }
}
