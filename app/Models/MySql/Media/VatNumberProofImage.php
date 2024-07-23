<?php

namespace App\Models\MySql\Media;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Media\VatNumberProofImage
 *
 * @property int $id
 * @property string|null $vat_number_proof_id
 * @property string $url
 * @property string $mime
 * @property Carbon $created_at
 * @property-read string $url_min
 * @method static Builder|VatNumberProofImage newModelQuery()
 * @method static Builder|VatNumberProofImage newQuery()
 * @method static Builder|VatNumberProofImage query()
 * @method static Builder|VatNumberProofImage whereCreatedAt($value)
 * @method static Builder|VatNumberProofImage whereId($value)
 * @method static Builder|VatNumberProofImage whereMime($value)
 * @method static Builder|VatNumberProofImage whereUrl($value)
 * @method static Builder|VatNumberProofImage whereVatNumberProofId($value)
 * @mixin Eloquent
 */
class VatNumberProofImage extends Model
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
    protected $table = 'vat_number_proof_images';

    /**
     * Provide default timestamps usage
     *
     * @var bool
     */
    public $timestamps = [
        'created_at'
    ];

    /**
     * Excluding updated at
     */
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vat_number_proof_id', 'url', 'mime'
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
