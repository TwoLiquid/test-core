<?php

namespace App\Models\MongoDb\Vybe\Request\Publish;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use MongoDB\Laravel\Eloquent\HybridRelations;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

/**
 * App\Models\MongoDb\Vybe\Request\Publish\VybePublishRequestSchedule
 *
 * @property string $_id
 * @property int $vybe_publish_request_id
 * @property Carbon $datetime_from
 * @property Carbon $datetime_to
 * @property-read VybePublishRequest $vybePublishRequest
 * @method static Builder|VybePublishRequestSchedule find(string $id)
 * @method static Builder|VybePublishRequestSchedule query()
 */
class VybePublishRequestSchedule extends Model
{
    use HybridRelations;

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
    protected $collection = 'vybe_publish_request_schedules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vybe_publish_request_id', 'datetime_from', 'datetime_to'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'datetime_from' => 'datetime',
        'datetime_to'   => 'datetime'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function vybePublishRequest() : BelongsTo
    {
        return $this->belongsTo(VybePublishRequest::class);
    }
}
