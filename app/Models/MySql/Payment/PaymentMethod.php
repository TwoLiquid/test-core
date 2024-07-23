<?php

namespace App\Models\MySql\Payment;

use App\Lists\Payment\Method\Payment\Status\PaymentStatusList;
use App\Lists\Payment\Method\Payment\Status\PaymentStatusListItem;
use App\Lists\Payment\Method\Withdrawal\Status\PaymentMethodWithdrawalStatusList;
use App\Lists\Payment\Method\Withdrawal\Status\PaymentMethodWithdrawalStatusListItem;
use App\Models\MongoDb\Payout\PayoutMethodRequest;
use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\User\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\HybridRelations;
use Spatie\Translatable\HasTranslations;
use Eloquent;

/**
 * App\Models\MySql\Payment\PaymentMethod
 *
 * @property int $id
 * @property int $payment_status_id
 * @property int $withdrawal_status_id
 * @property string $name
 * @property string $code
 * @property float|null $payment_fee
 * @property bool $order_form
 * @property array|null $display_name
 * @property array|null $duration_title
 * @property array|null $duration_amount
 * @property array|null $fee_title
 * @property array|null $fee_amount
 * @property bool $standard
 * @property bool $integrated
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, CountryPlace> $countryPlaces
 * @property-read int|null $country_places_count
 * @property-read Collection<int, CountryPlace> $excludedCountryPlaces
 * @property-read int|null $excluded_country_places_count
 * @property-read Collection<int, PaymentMethodField> $fields
 * @property-read int|null $fields_count
 * @property-read Collection<int, PayoutMethodRequest> $requests
 * @property-read int|null $requests_count
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static Builder|PaymentMethod addHybridHas(Relation $relation, string $operator = '>=', string $count = 1, string $boolean = 'and', ?Closure $callback = null)
 * @method static Builder|PaymentMethod newModelQuery()
 * @method static Builder|PaymentMethod newQuery()
 * @method static Builder|PaymentMethod onlyTrashed()
 * @method static Builder|PaymentMethod query()
 * @method static Builder|PaymentMethod whereCode($value)
 * @method static Builder|PaymentMethod whereCreatedAt($value)
 * @method static Builder|PaymentMethod whereDeletedAt($value)
 * @method static Builder|PaymentMethod whereDisplayName($value)
 * @method static Builder|PaymentMethod whereDurationAmount($value)
 * @method static Builder|PaymentMethod whereDurationTitle($value)
 * @method static Builder|PaymentMethod whereFeeAmount($value)
 * @method static Builder|PaymentMethod whereFeeTitle($value)
 * @method static Builder|PaymentMethod whereId($value)
 * @method static Builder|PaymentMethod whereIntegrated($value)
 * @method static Builder|PaymentMethod whereLocale(string $column, string $locale)
 * @method static Builder|PaymentMethod whereLocales(string $column, array $locales)
 * @method static Builder|PaymentMethod whereName($value)
 * @method static Builder|PaymentMethod whereOrderForm($value)
 * @method static Builder|PaymentMethod wherePaymentFee($value)
 * @method static Builder|PaymentMethod wherePaymentStatusId($value)
 * @method static Builder|PaymentMethod whereStandard($value)
 * @method static Builder|PaymentMethod whereUpdatedAt($value)
 * @method static Builder|PaymentMethod whereWithdrawalStatusId($value)
 * @method static Builder|PaymentMethod withTrashed()
 * @method static Builder|PaymentMethod withoutTrashed()
 * @mixin Eloquent
 */
class PaymentMethod extends Model
{
    use HasTranslations, SoftDeletes, HybridRelations;

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
    protected $table = 'payment_methods';

    /**
     * Translation field
     *
     * @var array
     */
    public array $translatable = [
        'display_name', 'duration_title', 'duration_amount',
        'fee_title', 'fee_amount'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_status_id', 'withdrawal_status_id', 'name', 'code', 'payment_fee', 'order_form',
        'display_name', 'duration_title', 'duration_amount', 'fee_title',
        'fee_amount', 'standard', 'integrated'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'order_form' => 'boolean',
        'standard'   => 'boolean',
        'integrated' => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function fields() : HasMany
    {
        return $this->hasMany(
            PaymentMethodField::class,
            'method_id',
            'id'
        );
    }

    /**
     * @return HasMany
     */
    public function requests() : HasMany
    {
        return $this->hasMany(
            PayoutMethodRequest::class,
            'method_id',
            'id'
        );
    }

    //--------------------------------------------------------------------------
    // Belongs to many relations

    /**
     * @return BelongsToMany
     */
    public function users() : BelongsToMany
    {
        return $this->belongsToMany(
            User::class
        );
    }

    /**
     * @return BelongsToMany
     */
    public function countryPlaces() : BelongsToMany
    {
        return $this->belongsToMany(
            CountryPlace::class,
            'country_place_payment_method',
            'method_id',
            'place_id',
            'id',
            'place_id'
        )->where(
            'country_place_payment_method.excluded',
            '=',
            0
        )->withPivot([
            'excluded'
        ]);
    }

    /**
     * @return BelongsToMany
     */
    public function excludedCountryPlaces() : BelongsToMany
    {
        return $this->belongsToMany(
            CountryPlace::class,
            'country_place_payment_method',
            'method_id',
            'place_id',
            'id',
            'place_id'
        )->where(
            'country_place_payment_method.excluded',
            '=',
            1
        )->withPivot([
            'excluded'
        ]);
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return PaymentStatusListItem|null
     */
    public function getPaymentStatus() : ?PaymentStatusListItem
    {
        return PaymentStatusList::getItem(
            $this->payment_status_id
        );
    }

    /**
     * @return PaymentMethodWithdrawalStatusListItem|null
     */
    public function getWithdrawalStatus() : ?PaymentMethodWithdrawalStatusListItem
    {
        return PaymentMethodWithdrawalStatusList::getItem(
            $this->withdrawal_status_id
        );
    }
}
