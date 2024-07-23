<?php

namespace App\Models\MySql\Media;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

/**
 * App\Models\MySql\Media\PaymentMethodImage
 *
 * @property int $id
 * @property int $method_id
 * @property string $url
 * @property string $mime
 * @property-read string $url_min
 * @method static Builder|PaymentMethodImage newModelQuery()
 * @method static Builder|PaymentMethodImage newQuery()
 * @method static Builder|PaymentMethodImage query()
 * @method static Builder|PaymentMethodImage whereId($value)
 * @method static Builder|PaymentMethodImage whereMethodId($value)
 * @method static Builder|PaymentMethodImage whereMime($value)
 * @method static Builder|PaymentMethodImage whereUrl($value)
 * @mixin Eloquent
 */
class PaymentMethodImage extends Model
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
    protected $table = 'payment_method_images';

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
        'method_id', 'url', 'mime'
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

    /**
     * @return string
     */
    public function getUrlMinAttribute() : string
    {
        return makeMinimizedMediaUrl(
            $this->url
        );
    }
}
