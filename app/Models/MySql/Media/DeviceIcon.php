<?php

namespace App\Models\MySql\Media;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

/**
 * App\Models\MySql\Media\DeviceIcon
 *
 * @property int $id
 * @property int $device_id
 * @property string $url
 * @property string $mime
 * @method static Builder|DeviceIcon newModelQuery()
 * @method static Builder|DeviceIcon newQuery()
 * @method static Builder|DeviceIcon query()
 * @method static Builder|DeviceIcon whereDeviceId($value)
 * @method static Builder|DeviceIcon whereId($value)
 * @method static Builder|DeviceIcon whereMime($value)
 * @method static Builder|DeviceIcon whereUrl($value)
 * @mixin Eloquent
 */
class DeviceIcon extends Model
{
    /**
     * Database connection name
     *
     * @var string
     */
    protected $connection = 'mysql_media';

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'device_icons';

    /**
     * Provide default timestamps usage
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'device_id', 'url', 'mime'
    ];

    //--------------------------------------------------------------------------
    // Magic accessors

    /**
     * @param string $value
     *
     * @return string
     */
    public function getUrlAttribute(string $value) : string
    {
        return makeMediaUrl($value);
    }
}
