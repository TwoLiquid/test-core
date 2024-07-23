<?php

namespace App\Models\MySql\Payment;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

/**
 * App\Models\MySql\Payment\PaymentHash
 *
 * @property int $id
 * @property string $external_id
 * @property string $hash
 * @method static Builder|PaymentHash newModelQuery()
 * @method static Builder|PaymentHash newQuery()
 * @method static Builder|PaymentHash query()
 * @method static Builder|PaymentHash whereExternalId($value)
 * @method static Builder|PaymentHash whereHash($value)
 * @method static Builder|PaymentHash whereId($value)
 * @mixin Eloquent
 */
class PaymentHash extends Model
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
    protected $table = 'payment_hashes';

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
        'external_id', 'hash'
    ];
}
