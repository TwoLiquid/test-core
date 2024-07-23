<?php

namespace App\Models\MySql\Alert;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Eloquent;

/**
 * App\Models\MySql\Alert\AlertProfanityWord
 *
 * @property int $id
 * @property int $filter_id
 * @property string $word
 * @property-read AlertProfanityFilter $filter
 * @method static Builder|AlertProfanityWord newModelQuery()
 * @method static Builder|AlertProfanityWord newQuery()
 * @method static Builder|AlertProfanityWord query()
 * @method static Builder|AlertProfanityWord whereFilterId($value)
 * @method static Builder|AlertProfanityWord whereId($value)
 * @method static Builder|AlertProfanityWord whereWord($value)
 * @mixin Eloquent
 */
class AlertProfanityWord extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'alert_profanity_words';

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
        'filter_id', 'word'
    ];

    //--------------------------------------------------------------------------
    // Belongs to relations

    /**
     * @return BelongsTo
     */
    public function filter() : BelongsTo
    {
        return $this->belongsTo(AlertProfanityFilter::class);
    }
}
