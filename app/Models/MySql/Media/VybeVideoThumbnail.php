<?php

namespace App\Models\MySql\Media;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Eloquent;

/**
 * App\Models\MySql\Media\VybeVideoThumbnail
 *
 * @property int $id
 * @property int $video_id
 * @property string $url
 * @property string $mime
 * @property-read string $url_min
 * @property-read VybeVideo $video
 * @method static Builder|VybeVideoThumbnail newModelQuery()
 * @method static Builder|VybeVideoThumbnail newQuery()
 * @method static Builder|VybeVideoThumbnail query()
 * @method static Builder|VybeVideoThumbnail whereId($value)
 * @method static Builder|VybeVideoThumbnail whereMime($value)
 * @method static Builder|VybeVideoThumbnail whereUrl($value)
 * @method static Builder|VybeVideoThumbnail whereVideoId($value)
 * @mixin Eloquent
 */
class VybeVideoThumbnail extends Model
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
    protected $table = 'vybe_video_thumbnails';

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
        'video_id', 'url', 'mime'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function video() : BelongsTo
    {
        return $this->belongsTo(
            VybeVideo::class,
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
