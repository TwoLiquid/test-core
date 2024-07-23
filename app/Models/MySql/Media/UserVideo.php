<?php

namespace App\Models\MySql\Media;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Eloquent;

/**
 * App\Models\MySql\Media\UserVideo
 *
 * @property int $id
 * @property int $auth_id
 * @property string|null $request_id
 * @property string $url
 * @property float|null $duration
 * @property string $mime
 * @property bool|null $declined
 * @property int $likes
 * @property-read string $url_min
 * @property-read UserVideoThumbnail|null $thumbnail
 * @method static Builder|UserVideo newModelQuery()
 * @method static Builder|UserVideo newQuery()
 * @method static Builder|UserVideo query()
 * @method static Builder|UserVideo whereAuthId($value)
 * @method static Builder|UserVideo whereDeclined($value)
 * @method static Builder|UserVideo whereDuration($value)
 * @method static Builder|UserVideo whereId($value)
 * @method static Builder|UserVideo whereLikes($value)
 * @method static Builder|UserVideo whereMime($value)
 * @method static Builder|UserVideo whereRequestId($value)
 * @method static Builder|UserVideo whereUrl($value)
 * @mixin Eloquent
 */
class UserVideo extends Model
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
    protected $table = 'user_videos';

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
        'auth_id', 'request_id', 'url', 'duration',
        'mime', 'declined', 'likes'
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
    // Has one relations

    /**
     * @return HasOne
     */
    public function thumbnail() : HasOne
    {
        return $this->hasOne(
            UserVideoThumbnail::class,
            'video_id',
            'id'
        );
    }

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
