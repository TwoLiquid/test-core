<?php

namespace App\Models\MySql\Tip;

use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Lists\Invoice\Status\InvoiceStatusListItem;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Lists\Invoice\Type\InvoiceTypeListItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Tip\TipInvoice
 *
 * @property int $id
 * @property int $tip_id
 * @property int $type_id
 * @property int $status_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $full_id
 * @property-read Tip $tip
 * @method static Builder|TipInvoice newModelQuery()
 * @method static Builder|TipInvoice newQuery()
 * @method static Builder|TipInvoice onlyTrashed()
 * @method static Builder|TipInvoice query()
 * @method static Builder|TipInvoice whereCreatedAt($value)
 * @method static Builder|TipInvoice whereDeletedAt($value)
 * @method static Builder|TipInvoice whereId($value)
 * @method static Builder|TipInvoice whereStatusId($value)
 * @method static Builder|TipInvoice whereTipId($value)
 * @method static Builder|TipInvoice whereTypeId($value)
 * @method static Builder|TipInvoice whereUpdatedAt($value)
 * @method static Builder|TipInvoice withTrashed()
 * @method static Builder|TipInvoice withoutTrashed()
 * @mixin Eloquent
 */
class TipInvoice extends Model
{
    use SoftDeletes;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'tip_invoices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tip_id', 'type_id', 'status_id'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function tip() : BelongsTo
    {
        return $this->belongsTo(Tip::class);
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return InvoiceTypeListItem|null
     */
    public function getType() : ?InvoiceTypeListItem
    {
        return InvoiceTypeList::getItem(
            $this->type_id
        );
    }

    /**
     * @return InvoiceStatusListItem|null
     */
    public function getStatus() : ?InvoiceStatusListItem
    {
        return InvoiceStatusList::getItem(
            $this->status_id
        );
    }

    //--------------------------------------------------------------------------
    // Accessors

    /**
     * @return string
     */
    public function getFullIdAttribute() : string
    {
        return $this->getType()->idPrefix . $this->id;
    }
}
