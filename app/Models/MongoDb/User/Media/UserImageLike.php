<?php

namespace App\Models\MongoDb\User\Media;

use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MongoDB\Laravel\Eloquent\Model;

/**
 * App\Models\MongoDb\User\Media\UserImageLike
 *
 * @property string $_id
 * @property int $user_id
 * @property int $image_id
 * @property-read User $user
 * @method static Builder|UserImageLike find(string $id)
 * @method static Builder|UserImageLike query()
 */
class UserImageLike extends Model
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
    protected $collection = 'user_image_likes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'image_id'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
