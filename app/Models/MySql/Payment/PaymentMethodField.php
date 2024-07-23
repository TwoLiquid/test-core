<?php

namespace App\Models\MySql\Payment;

use App\Lists\Payment\Method\Field\Type\PaymentMethodFieldTypeList;
use App\Lists\Payment\Method\Field\Type\PaymentMethodFieldTypeListItem;
use App\Models\MySql\Payment\Pivot\PaymentMethodFieldUserPivot;
use App\Models\MySql\User\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use MongoDB\Laravel\Eloquent\HybridRelations;
use Spatie\Translatable\HasTranslations;
use Eloquent;

/**
 * App\Models\MySql\Payment\PaymentMethodField
 *
 * @property int $id
 * @property int $method_id
 * @property int $type_id
 * @property array $title
 * @property array $placeholder
 * @property array|null $tooltip
 * @property-read PaymentMethod $method
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static Builder|PaymentMethodField addHybridHas(Relation $relation, string $operator = '>=', string $count = 1, string $boolean = 'and', ?Closure $callback = null)
 * @method static Builder|PaymentMethodField newModelQuery()
 * @method static Builder|PaymentMethodField newQuery()
 * @method static Builder|PaymentMethodField query()
 * @method static Builder|PaymentMethodField whereId($value)
 * @method static Builder|PaymentMethodField whereLocale(string $column, string $locale)
 * @method static Builder|PaymentMethodField whereLocales(string $column, array $locales)
 * @method static Builder|PaymentMethodField whereMethodId($value)
 * @method static Builder|PaymentMethodField wherePlaceholder($value)
 * @method static Builder|PaymentMethodField whereTitle($value)
 * @method static Builder|PaymentMethodField whereTooltip($value)
 * @method static Builder|PaymentMethodField whereTypeId($value)
 * @mixin Eloquent
 */
class PaymentMethodField extends Model
{
    use HasTranslations, HybridRelations;

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
    protected $table = 'payment_method_fields';

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
    public array $translatable = [
        'title', 'placeholder', 'tooltip'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'method_id', 'type_id', 'title', 'placeholder', 'tooltip'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function method() : BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    //--------------------------------------------------------------------------
    // Belongs to many relations

    /**
     * @return BelongsToMany
     */
    public function users() : BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'payment_method_field_user',
            'field_id',
            'user_id'
        )->withPivot([
            'value'
        ])->using(
            PaymentMethodFieldUserPivot::class
        );
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return PaymentMethodFieldTypeListItem|null
     */
    public function getType() : ?PaymentMethodFieldTypeListItem
    {
        return PaymentMethodFieldTypeList::getItem(
            $this->type_id
        );
    }
}
