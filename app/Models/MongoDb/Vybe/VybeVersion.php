<?php

namespace App\Models\MongoDb\Vybe;

use App\Models\MySql\Vybe\Vybe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\Vybe\VybeVersion
 *
 * @property int $_id
 * @property int $vybe_id
 * @property int $number
 * @property string $type
 * @property string $title
 * @property string $category
 * @property string $subcategory
 * @property string $activity
 * @property array $devices
 * @property int $vybe_handling_fee
 * @property string $period
 * @property int $user_count
 * @property array $appearance_cases
 * @property array $schedules
 * @property int $order_advance
 * @property array $images_ids
 * @property array $videos_ids
 * @property string $access
 * @property string $showcase
 * @property string $order_accept
 * @property string $age_limit
 * @property string $status
 * @property-read Vybe $vybe
 * @property-read Carbon $created_at
 * @method static Builder|VybeVersion find(string $id)
 * @method static Builder|VybeVersion query()
 */
class VybeVersion extends Model
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
    protected $collection = 'vybe_versions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vybe_id', 'number', 'type', 'title', 'category', 'subcategory', 'activity', 'devices',
        'vybe_handling_fee', 'period', 'user_count', 'appearance_cases', 'schedules', 'order_advance',
        'images_ids', 'videos_ids', 'access', 'showcase', 'order_accept', 'age_limit', 'status'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'devices'          => 'array',
        'appearance_cases' => 'array',
        'schedules'        => 'array',
        'images_ids'       => 'array',
        'videos_ids'       => 'array'
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
}
