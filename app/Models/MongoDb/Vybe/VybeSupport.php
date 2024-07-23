<?php

namespace App\Models\MongoDb\Vybe;

use App\Models\MySql\Category;
use App\Models\MySql\Vybe\Vybe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\HybridRelations;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\Vybe\VybeSupport
 *
 * @property string $_id
 * @property int $vybe_id
 * @property int $category_id
 * @property string $category_suggestion
 * @property int $subcategory_id
 * @property string $subcategory_suggestion
 * @property string $activity_suggestion
 * @property string $device_suggestion
 * @property array $devices_ids
 * @property-read Vybe $vybe
 * @property-read Category $category
 * @property-read Category $subcategory
 * @method static Builder|VybeSupport find(string $id)
 * @method static Builder|VybeSupport query()
 */
class VybeSupport extends Model
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
    protected $collection = 'vybe_supports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vybe_id', 'category_id', 'category_suggestion', 'subcategory_id',
        'subcategory_suggestion', 'activity_suggestion', 'device_suggestion',
        'devices_ids'
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

    /**
     * @return BelongsTo
     */
    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function subcategory() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
