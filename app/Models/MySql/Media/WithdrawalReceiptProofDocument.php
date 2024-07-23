<?php

namespace App\Models\MySql\Media;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

/**
 * App\Models\MySql\Media\WithdrawalReceiptProofDocument
 *
 * @property int $id
 * @property int $receipt_id
 * @property string $url
 * @property string $mime
 * @method static Builder|WithdrawalReceiptProofDocument newModelQuery()
 * @method static Builder|WithdrawalReceiptProofDocument newQuery()
 * @method static Builder|WithdrawalReceiptProofDocument query()
 * @method static Builder|WithdrawalReceiptProofDocument whereId($value)
 * @method static Builder|WithdrawalReceiptProofDocument whereMime($value)
 * @method static Builder|WithdrawalReceiptProofDocument whereReceiptId($value)
 * @method static Builder|WithdrawalReceiptProofDocument whereUrl($value)
 * @mixin Eloquent
 */
class WithdrawalReceiptProofDocument extends Model
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
    protected $table = 'withdrawal_receipt_proof_documents';

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
}
