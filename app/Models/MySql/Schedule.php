<?php

namespace App\Models\MySql;

use App\Models\MySql\Vybe\Vybe;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Schedule
 *
 * @property int $id
 * @property int $vybe_id
 * @property Carbon|null $datetime_from
 * @property Carbon|null $datetime_to
 * @property-read Vybe $vybe
 * @method static Builder|Schedule newModelQuery()
 * @method static Builder|Schedule newQuery()
 * @method static Builder|Schedule query()
 * @method static Builder|Schedule whereDatetimeFrom($value)
 * @method static Builder|Schedule whereDatetimeTo($value)
 * @method static Builder|Schedule whereId($value)
 * @method static Builder|Schedule whereVybeId($value)
 * @mixin Eloquent
 */
class Schedule extends Model
{
    /**
     * Database connection type
     *
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'schedules';

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
        'vybe_id', 'datetime_from', 'datetime_to'
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
    public function vybe() : BelongsTo
    {
        return $this->belongsTo(Vybe::class);
    }
}
