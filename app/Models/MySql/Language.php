<?php

namespace App\Models\MySql;

use App\Lists\Language\LanguageList;
use App\Lists\Language\LanguageListItem;
use App\Lists\Language\Level\LanguageLevelList;
use App\Lists\Language\Level\LanguageLevelListItem;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Eloquent;

/**
 * App\Models\MySql\Language
 *
 * @property int $id
 * @property int $user_id
 * @property int $language_id
 * @property int $level_id
 * @property-read User $user
 * @method static Builder|Language newModelQuery()
 * @method static Builder|Language newQuery()
 * @method static Builder|Language query()
 * @method static Builder|Language whereId($value)
 * @method static Builder|Language whereLanguageId($value)
 * @method static Builder|Language whereLevelId($value)
 * @method static Builder|Language whereUserId($value)
 * @mixin Eloquent
 */
class Language extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'languages';

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
        'user_id', 'language_id', 'level_id'
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

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return LanguageListItem|null
     */
    public function getLanguage() : ?LanguageListItem
    {
        return LanguageList::getItem(
            $this->language_id
        );
    }

    /**
     * @return LanguageLevelListItem|null
     */
    public function getLevel() : ?LanguageLevelListItem
    {
        return LanguageLevelList::getItem(
            $this->level_id
        );
    }
}
