<?php

namespace App\Models\MySql;

use App\Models\MongoDb\User\Billing\VatNumberProof;
use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\Place\RegionPlace;
use App\Models\MySql\User\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use MongoDB\Laravel\Eloquent\HybridRelations;
use MongoDB\Laravel\Relations\HasMany;
use Eloquent;

/**
 * App\Models\MySql\Billing
 *
 * @property int $id
 * @property int $user_id
 * @property string $country_place_id
 * @property string|null $region_place_id
 * @property string|null $phone_code_country_place_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $city
 * @property string|null $postal_code
 * @property string|null $address
 * @property string|null $phone
 * @property bool $business_info
 * @property string|null $company_name
 * @property string|null $vat_number
 * @property-read CountryPlace $countryPlace
 * @property-read CountryPlace|null $phoneCodeCountryPlace
 * @property-read RegionPlace|null $regionPlace
 * @property-read User $user
 * @property-read Collection<int, VatNumberProof> $vatNumberProofs
 * @property-read int|null $vat_number_proofs_count
 * @method static Builder|Billing addHybridHas(Relation $relation, string $operator = '>=', string $count = 1, string $boolean = 'and', ?Closure $callback = null)
 * @method static Builder|Billing newModelQuery()
 * @method static Builder|Billing newQuery()
 * @method static Builder|Billing query()
 * @method static Builder|Billing whereAddress($value)
 * @method static Builder|Billing whereBusinessInfo($value)
 * @method static Builder|Billing whereCity($value)
 * @method static Builder|Billing whereCompanyName($value)
 * @method static Builder|Billing whereCountryPlaceId($value)
 * @method static Builder|Billing whereFirstName($value)
 * @method static Builder|Billing whereId($value)
 * @method static Builder|Billing whereLastName($value)
 * @method static Builder|Billing wherePhone($value)
 * @method static Builder|Billing wherePhoneCodeCountryPlaceId($value)
 * @method static Builder|Billing wherePostalCode($value)
 * @method static Builder|Billing whereRegionPlaceId($value)
 * @method static Builder|Billing whereUserId($value)
 * @method static Builder|Billing whereVatNumber($value)
 * @mixin Eloquent
 */
class Billing extends Model
{
    use HybridRelations;

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
    protected $table = 'billings';

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
        'user_id', 'country_place_id', 'region_place_id', 'phone_code_country_place_id',
        'first_name', 'last_name', 'city', 'postal_code', 'address', 'phone',
        'business_info', 'company_name', 'vat_number'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'business_info' => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

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
    public function regionPlace() : BelongsTo
    {
        return $this->belongsTo(
            RegionPlace::class,
            'region_place_id',
            'place_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function phoneCodeCountryPlace() : BelongsTo
    {
        return $this->belongsTo(
            CountryPlace::class,
            'phone_code_country_place_id',
            'place_id'
        );
    }


    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function vatNumberProofs() : HasMany
    {
        return $this->hasMany(
            VatNumberProof::class,
            'billing_id',
            'id'
        );
    }

    //--------------------------------------------------------------------------
    // Methods

    /**
     * @return VatNumberProof|null
     */
    public function getVatNumberProof() : ?VatNumberProof
    {
        return $this->vatNumberProofs
            ->where('country_place_id', '=', $this->country_place_id)
            ->where('company_name', '=', $this->company_name)
            ->where('vat_number', '=', $this->vat_number)
            ->first();
    }
}
