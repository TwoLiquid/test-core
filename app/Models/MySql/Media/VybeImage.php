<?php

namespace App\Models\MySql\Media;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

/**
 * App\Models\MySql\Media\VybeImage
 *
 * @property int $id
 * @property string $url
 * @property string $mime
 * @property bool $main
 * @property bool $declined
 * @property-read string $url_min
 * @method static Builder|VybeImage newModelQuery()
 * @method static Builder|VybeImage newQuery()
 * @method static Builder|VybeImage query()
 * @method static Builder|VybeImage whereDeclined($value)
 * @method static Builder|VybeImage whereId($value)
 * @method static Builder|VybeImage whereMain($value)
 * @method static Builder|VybeImage whereMime($value)
 * @method static Builder|VybeImage whereUrl($value)
 * @mixin Eloquent
 */
class VybeImage extends Model
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
    protected $table = 'vybe_images';

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
        'url', 'mime', 'main', 'declined'
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
