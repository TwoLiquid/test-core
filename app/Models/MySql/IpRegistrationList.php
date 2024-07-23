<?php

namespace App\Models\MySql;

use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Eloquent;

/**
 * App\Models\MySql\IpRegistrationList
 *
 * @property int $id
 * @property int $user_id
 * @property string $ip_address
 * @property bool $vpn
 * @property-read Collection<int, IpRegistrationList> $duplicates
 * @property-read int|null $duplicates_count
 * @property-read User $user
 * @method static Builder|IpRegistrationList newModelQuery()
 * @method static Builder|IpRegistrationList newQuery()
 * @method static Builder|IpRegistrationList query()
 * @method static Builder|IpRegistrationList whereId($value)
 * @method static Builder|IpRegistrationList whereIpAddress($value)
 * @method static Builder|IpRegistrationList whereUserId($value)
 * @method static Builder|IpRegistrationList whereVpn($value)
 * @mixin Eloquent
 */
class IpRegistrationList extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'ip_registration_list';

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
        'user_id', 'ip_address', 'vpn'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'vpn' => 'boolean'
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
    // Has many relations

    /**
     * @return HasMany
     */
    public function duplicates() : HasMany
    {
        return $this->hasMany(
            IpRegistrationList::class,
            'ip_address',
            'ip_address'
        );
    }
}
