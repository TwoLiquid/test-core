<?php

namespace App\Models\MySql;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

/**
 * App\Models\MySql\RequestSetting
 *
 * @property int $id
 * @property string $block_code
 * @property string $setting_code
 * @property array|null $value
 * @method static Builder|RequestSetting newModelQuery()
 * @method static Builder|RequestSetting newQuery()
 * @method static Builder|RequestSetting query()
 * @method static Builder|RequestSetting whereBlockCode($value)
 * @method static Builder|RequestSetting whereId($value)
 * @method static Builder|RequestSetting whereSettingCode($value)
 * @method static Builder|RequestSetting whereValue($value)
 * @mixin Eloquent
 */
class RequestSetting extends Model
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
    protected $table = 'request_settings';

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
        'block_code', 'setting_code', 'value'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'array'
    ];
}
