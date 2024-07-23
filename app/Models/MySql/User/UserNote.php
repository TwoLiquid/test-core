<?php

namespace App\Models\MySql\User;

use App\Models\MySql\Admin\Admin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\User\UserNote
 *
 * @property int $id
 * @property int $user_id
 * @property int $admin_id
 * @property string $text
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Admin $admin
 * @property-read User $user
 * @method static Builder|UserNote newModelQuery()
 * @method static Builder|UserNote newQuery()
 * @method static Builder|UserNote query()
 * @method static Builder|UserNote whereAdminId($value)
 * @method static Builder|UserNote whereCreatedAt($value)
 * @method static Builder|UserNote whereId($value)
 * @method static Builder|UserNote whereText($value)
 * @method static Builder|UserNote whereUpdatedAt($value)
 * @method static Builder|UserNote whereUserId($value)
 * @mixin Eloquent
 */
class UserNote extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'user_notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'admin_id', 'text'
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

    /**
     * @return BelongsTo
     */
    public function admin() : BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
