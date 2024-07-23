<?php

namespace App\Models\MySql\Vybe;

use App\Lists\Setting\Type\SettingTypeList;
use App\Lists\Setting\Type\SettingTypeListItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Eloquent;

/**
 * App\Models\MySql\Vybe\VybeSetting
 *
 * @property int $id
 * @property int $type_id
 * @property int|null $vybe_id
 * @property string $block_code
 * @property string $setting_code
 * @property array|null $value
 * @property bool|null $active
 * @property-read Vybe|null $vybe
 * @method static Builder|VybeSetting newModelQuery()
 * @method static Builder|VybeSetting newQuery()
 * @method static Builder|VybeSetting query()
 * @method static Builder|VybeSetting whereActive($value)
 * @method static Builder|VybeSetting whereBlockCode($value)
 * @method static Builder|VybeSetting whereId($value)
 * @method static Builder|VybeSetting whereSettingCode($value)
 * @method static Builder|VybeSetting whereTypeId($value)
 * @method static Builder|VybeSetting whereValue($value)
 * @method static Builder|VybeSetting whereVybeId($value)
 * @mixin Eloquent
 */
class VybeSetting extends Model
{
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
    protected $table = 'vybe_settings';

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
        'type_id', 'vybe_id', 'block_code', 'setting_code', 'value', 'active'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value'  => 'array',
        'active' => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function vybe() : BelongsTo
    {
        return $this->belongsTo(Vybe::class);
    }

    //--------------------------------------------------------------------------
    // Accessors

    /**
     * @return SettingTypeListItem
     */
    public function getType() : SettingTypeListItem
    {
        return SettingTypeList::getItem(
            $this->type_id
        );
    }
}
