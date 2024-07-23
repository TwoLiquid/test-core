<?php

namespace App\Models\MySql\User;

use App\Lists\Setting\Type\SettingTypeList;
use App\Lists\Setting\Type\SettingTypeListItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Eloquent;

/**
 * App\Models\MySql\User\UserSetting
 *
 * @property int $id
 * @property int $type_id
 * @property int|null $user_id
 * @property string $block_code
 * @property string $setting_code
 * @property array|null $value
 * @property bool|null $active
 * @property-read User|null $user
 * @method static Builder|UserSetting newModelQuery()
 * @method static Builder|UserSetting newQuery()
 * @method static Builder|UserSetting query()
 * @method static Builder|UserSetting whereActive($value)
 * @method static Builder|UserSetting whereBlockCode($value)
 * @method static Builder|UserSetting whereId($value)
 * @method static Builder|UserSetting whereSettingCode($value)
 * @method static Builder|UserSetting whereTypeId($value)
 * @method static Builder|UserSetting whereUserId($value)
 * @method static Builder|UserSetting whereValue($value)
 * @mixin Eloquent
 */
class UserSetting extends Model
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
    protected $table = 'user_settings';

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
        'type_id', 'user_id', 'block_code', 'setting_code', 'value', 'active'
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
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
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
