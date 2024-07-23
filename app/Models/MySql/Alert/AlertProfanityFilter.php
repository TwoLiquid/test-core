<?php

namespace App\Models\MySql\Alert;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Eloquent;

/**
 * App\Models\MySql\Alert\AlertProfanityFilter
 *
 * @property int $id
 * @property int $alert_id
 * @property bool $replace
 * @property string $replace_with
 * @property bool $hide_messages
 * @property bool $bad_words
 * @property-read Alert $alert
 * @property-read Collection<int, AlertProfanityWord> $words
 * @property-read int|null $words_count
 * @method static Builder|AlertProfanityFilter newModelQuery()
 * @method static Builder|AlertProfanityFilter newQuery()
 * @method static Builder|AlertProfanityFilter query()
 * @method static Builder|AlertProfanityFilter whereAlertId($value)
 * @method static Builder|AlertProfanityFilter whereBadWords($value)
 * @method static Builder|AlertProfanityFilter whereHideMessages($value)
 * @method static Builder|AlertProfanityFilter whereId($value)
 * @method static Builder|AlertProfanityFilter whereReplace($value)
 * @method static Builder|AlertProfanityFilter whereReplaceWith($value)
 * @mixin Eloquent
 */
class AlertProfanityFilter extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'alert_profanity_filters';

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
        'alert_id', 'replace', 'replace_with', 'hide_messages', 'bad_words'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'replace'       => 'boolean',
        'hide_messages' => 'boolean',
        'bad_words'     => 'boolean'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function alert() : BelongsTo
    {
        return $this->belongsTo(Alert::class);
    }

    //--------------------------------------------------------------------------
    // Has many relations

    /**
     * @return HasMany
     */
    public function words() : HasMany
    {
        return $this->hasMany(
            AlertProfanityWord::class,
            'filter_id',
            'id'
        );
    }
}
