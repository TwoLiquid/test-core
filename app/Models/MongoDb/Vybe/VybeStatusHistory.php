<?php

namespace App\Models\MongoDb\Vybe;

use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Status\VybeStatusListItem;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\Vybe\VybeStatusHistory
 *
 * @property int $id
 * @property int|null $vybe_id
 * @property int|null $status_id
 * @property-read Vybe $vybe
 * @method static Builder|VybeStatusHistory find(string $id)
 * @method static Builder|VybeStatusHistory query()
 */
class VybeStatusHistory extends Model
{
    /**
     * Database connection type
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * Database collection name
     *
     * @var string
     */
    protected $collection = 'vybe_status_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vybe_id', 'status_id'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function vybe() : BelongsTo
    {
        return $this->belongsTo(Vybe::class);
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return VybeStatusListItem|null
     */
    public function getStatus() : ?VybeStatusListItem
    {
        return VybeStatusList::getItem(
            $this->status_id
        );
    }
}
