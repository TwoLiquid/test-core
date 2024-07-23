<?php

namespace App\Models\MySql\Media;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

/**
 * App\Models\MySql\Media\WithdrawalReceiptProofImage
 *
 * @property int $id
 * @property int $receipt_id
 * @property string $url
 * @property string $mime
 * @property-read string $url_min
 * @method static Builder|WithdrawalReceiptProofImage newModelQuery()
 * @method static Builder|WithdrawalReceiptProofImage newQuery()
 * @method static Builder|WithdrawalReceiptProofImage query()
 * @method static Builder|WithdrawalReceiptProofImage whereId($value)
 * @method static Builder|WithdrawalReceiptProofImage whereMime($value)
 * @method static Builder|WithdrawalReceiptProofImage whereReceiptId($value)
 * @method static Builder|WithdrawalReceiptProofImage whereUrl($value)
 * @mixin Eloquent
 */
class WithdrawalReceiptProofImage extends Model
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
    protected $table = 'withdrawal_receipt_proof_images';

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
        'receipt_id', 'url', 'mime'
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
