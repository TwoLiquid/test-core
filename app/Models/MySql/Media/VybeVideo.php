<?php

namespace App\Models\MySql\Media;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Eloquent;

/**
 * App\Models\MySql\Media\VybeVideo
 *
 * @property int $id
 * @property string $url
 * @property float|null $duration
 * @property string $mime
 * @property bool $main
 * @property bool $declined
 * @property-read VybeVideoThumbnail|null $thumbnail
 * @method static Builder|VybeVideo newModelQuery()
 * @method static Builder|VybeVideo newQuery()
 * @method static Builder|VybeVideo query()
 * @method static Builder|VybeVideo whereDeclined($value)
 * @method static Builder|VybeVideo whereDuration($value)
 * @method static Builder|VybeVideo whereId($value)
 * @method static Builder|VybeVideo whereMain($value)
 * @method static Builder|VybeVideo whereMime($value)
 * @method static Builder|VybeVideo whereUrl($value)
 * @mixin Eloquent
 */
class VybeVideo extends Model
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
    protected $table = 'vybe_videos';

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
        'url', 'duration', 'mime', 'main', 'declined'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'main'     => 'boolean',
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
            VybeVideoThumbnail::class,
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
}
