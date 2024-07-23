<?php

namespace App\Models\MongoDb\User\Billing;

use App\Lists\Language\LanguageList;
use App\Lists\Language\LanguageListItem;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Lists\ToastMessage\Type\ToastMessageTypeListItem;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\Place\RegionPlace;
use App\Models\MySql\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\User\Billing\BillingChangeRequest
 *
 * @property string $_id
 * @property int $user_id
 * @property string $country_place_id
 * @property int $country_place_status_id
 * @property string $previous_country_place_id
 * @property string $region_place_id
 * @property bool $shown
 * @property int $request_status_id
 * @property int $toast_message_type_id
 * @property string $toast_message_text
 * @property int $admin_id
 * @property int $language_id
 * @property-read Carbon $created_at
 * @property-read User $user
 * @property-read CountryPlace $countryPlace
 * @property-read CountryPlace $previousCountryPlace
 * @property-read RegionPlace $regionPlace
 * @property-read Admin $admin
 * @method static Builder|BillingChangeRequest find(string $id)
 * @method static Builder|BillingChangeRequest query()
 */
class BillingChangeRequest extends Model
{
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
    protected $collection = 'billing_change_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'country_place_id', 'country_place_status_id', 'previous_country_place_id',
        'region_place_id', 'shown', 'request_status_id', 'toast_message_type_id',
        'toast_message_text', 'admin_id', 'language_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'shown' => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function admin() : BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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

    /**
     * @return BelongsTo
     */
    public function previousCountryPlace() : BelongsTo
    {
        return $this->belongsTo(
            CountryPlace::class,
            'previous_country_place_id',
            'place_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function regionPlace() : BelongsTo
    {
        return $this->belongsTo(
            RegionPlace::class,
            'region_place_id',
            'place_id'
        );
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return RequestFieldStatusListItem|null
     */
    public function getCountryPlaceStatus() : ?RequestFieldStatusListItem
    {
        return RequestFieldStatusList::getItem(
            $this->country_place_status_id
        );
    }

    /**
     * @return ToastMessageTypeListItem|null
     */
    public function getToastMessageType() : ?ToastMessageTypeListItem
    {
        return ToastMessageTypeList::getItem(
            $this->toast_message_type_id
        );
    }

    /**
     * @return RequestStatusListItem|null
     */
    public function getRequestStatus() : ?RequestStatusListItem
    {
        return RequestStatusList::getItem(
            $this->request_status_id
        );
    }

    /**
     * @return LanguageListItem|null
     */
    public function getLanguage() : ?LanguageListItem
    {
        return LanguageList::getItem(
            $this->language_id
        );
    }
}
