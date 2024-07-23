<?php

namespace App\Models\MySql\Media;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

/**
 * App\Models\MySql\Media\UserIdVerificationImage
 *
 * @property int $id
 * @property int $auth_id
 * @property string|null $request_id
 * @property string $url
 * @property string $mime
 * @property bool|null $declined
 * @property-read string $url_min
 * @method static Builder|UserIdVerificationImage newModelQuery()
 * @method static Builder|UserIdVerificationImage newQuery()
 * @method static Builder|UserIdVerificationImage query()
 * @method static Builder|UserIdVerificationImage whereAuthId($value)
 * @method static Builder|UserIdVerificationImage whereDeclined($value)
 * @method static Builder|UserIdVerificationImage whereId($value)
 * @method static Builder|UserIdVerificationImage whereMime($value)
 * @method static Builder|UserIdVerificationImage whereRequestId($value)
 * @method static Builder|UserIdVerificationImage whereUrl($value)
 * @mixin Eloquent
 */
class UserIdVerificationImage extends Model
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
    protected $table = 'user_id_verification_images';

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
        'auth_id', 'request_id', 'url', 'mime', 'declined'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'declined' => 'boolean'
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
