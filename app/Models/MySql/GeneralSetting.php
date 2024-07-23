<?php

namespace App\Models\MySql;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

/**
 * App\Models\MySql\GeneralSetting
 *
 * @property int $id
 * @property string $block_code
 * @property string $setting_code
 * @property array|null $value
 * @method static Builder|GeneralSetting newModelQuery()
 * @method static Builder|GeneralSetting newQuery()
 * @method static Builder|GeneralSetting query()
 * @method static Builder|GeneralSetting whereBlockCode($value)
 * @method static Builder|GeneralSetting whereId($value)
 * @method static Builder|GeneralSetting whereSettingCode($value)
 * @method static Builder|GeneralSetting whereValue($value)
 * @mixin Eloquent
 */
class GeneralSetting extends Model
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
    protected $table = 'general_settings';

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
