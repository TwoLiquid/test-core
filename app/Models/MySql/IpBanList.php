<?php

namespace App\Models\MySql;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

/**
 * App\Models\MySql\IpBanList
 *
 * @property int $id
 * @property string $ip_address
 * @method static Builder|IpBanList newModelQuery()
 * @method static Builder|IpBanList newQuery()
 * @method static Builder|IpBanList query()
 * @method static Builder|IpBanList whereId($value)
 * @method static Builder|IpBanList whereIpAddress($value)
 * @mixin Eloquent
 */
class IpBanList extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'ip_ban_list';

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
        'ip_address'
    ];
}
