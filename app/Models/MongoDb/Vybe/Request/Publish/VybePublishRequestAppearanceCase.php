<?php

namespace App\Models\MongoDb\Vybe\Request\Publish;

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
 * App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequestAppearanceCase
 *
 * @property string $_id
 * @property int $vybe_publish_request_id
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
 * @property string $city_place_id
 * @property string $previous_city_place_id
 * @property int $city_place_status_id
 * @property bool $enabled
 * @property int $csau_suggestion_id
 * @property-read VybePublishRequest $vybePublishRequest
 * @property-read Unit $unit
 * @property-read CityPlace $cityPlace
 * @property-read CsauSuggestion $csauSuggestion
 * @method static Builder|VybePublishRequestAppearanceCase find(string $id)
 * @method static Builder|VybePublishRequestAppearanceCase query()
 */
class VybePublishRequestAppearanceCase extends Model
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
    protected $collection = 'vybe_publish_request_appearance_cases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vybe_publish_request_id', 'appearance_id', 'price', 'previous_price', 'price_status_id', 'unit_id',
        'previous_unit_id', 'unit_suggestion', 'unit_status_id', 'description', 'previous_description',
        'description_status_id', 'platforms_ids', 'previous_platforms_ids', 'platforms_status_id',
        'same_location', 'city_place_id', 'previous_city_place_id', 'city_place_status_id',
        'enabled', 'csau_suggestion_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'platforms_ids' => 'array',
        'same_location' => 'boolean',
        'enabled'       => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return MongoBelongsTo
     */
    public function vybePublishRequest() : MongoBelongsTo
    {
        return $this->belongsTo(VybePublishRequest::class);
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
    public function cityPlace() : BelongsTo
    {
        return $this->belongsTo(
            CityPlace::class,
            'city_place_id',
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
