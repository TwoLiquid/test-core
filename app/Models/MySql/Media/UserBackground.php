<?php

namespace App\Models\MySql\Media;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

/**
 * App\Models\MySql\Media\UserBackground
 *
 * @property int $id
 * @property int $auth_id
 * @property string|null $request_id
 * @property string $url
 * @property string $mime
 * @property bool|null $declined
 * @property-read string $url_min
 * @method static Builder|UserBackground newModelQuery()
 * @method static Builder|UserBackground newQuery()
 * @method static Builder|UserBackground query()
 * @method static Builder|UserBackground whereAuthId($value)
 * @method static Builder|UserBackground whereDeclined($value)
 * @method static Builder|UserBackground whereId($value)
 * @method static Builder|UserBackground whereMime($value)
 * @method static Builder|UserBackground whereRequestId($value)
 * @method static Builder|UserBackground whereUrl($value)
 * @mixin Eloquent
 */
class UserBackground extends Model
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
    protected $table = 'user_backgrounds';

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
