<?php

namespace App\Models\MySql\Alert;

use App\Lists\Alert\Align\AlertAlignList;
use App\Lists\Alert\Align\AlertAlignListItem;
use App\Lists\Alert\Animation\AlertAnimationList;
use App\Lists\Alert\Animation\AlertAnimationListItem;
use App\Lists\Alert\Cover\AlertCoverList;
use App\Lists\Alert\Cover\AlertCoverListItem;
use App\Lists\Alert\Logo\Align\AlertLogoAlignList;
use App\Lists\Alert\Logo\Align\AlertLogoAlignListItem;
use App\Lists\Alert\Text\Font\AlertTextFontList;
use App\Lists\Alert\Text\Font\AlertTextFontListItem;
use App\Lists\Alert\Text\Style\AlertTextStyleList;
use App\Lists\Alert\Text\Style\AlertTextStyleListItem;
use App\Lists\Alert\Type\AlertTypeList;
use App\Lists\Alert\Type\AlertTypeListItem;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Eloquent;

/**
 * App\Models\MySql\Alert\Alert
 *
 * @property int $id
 * @property int $user_id
 * @property int $type_id
 * @property int $animation_id
 * @property int $align_id
 * @property int $text_font_id
 * @property int $text_style_id
 * @property int $logo_align_id
 * @property int $cover_id
 * @property int $duration
 * @property string $text_font_color
 * @property int $text_font_size
 * @property string $logo_color
 * @property int $volume
 * @property string|null $username
 * @property float|null $amount
 * @property string|null $action
 * @property string|null $message
 * @property string|null $widget_url
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read AlertProfanityFilter|null $filter
 * @property-read User $user
 * @method static Builder|Alert newModelQuery()
 * @method static Builder|Alert newQuery()
 * @method static Builder|Alert onlyTrashed()
 * @method static Builder|Alert query()
 * @method static Builder|Alert whereAction($value)
 * @method static Builder|Alert whereAlignId($value)
 * @method static Builder|Alert whereAmount($value)
 * @method static Builder|Alert whereAnimationId($value)
 * @method static Builder|Alert whereCoverId($value)
 * @method static Builder|Alert whereCreatedAt($value)
 * @method static Builder|Alert whereDeletedAt($value)
 * @method static Builder|Alert whereDuration($value)
 * @method static Builder|Alert whereId($value)
 * @method static Builder|Alert whereLogoAlignId($value)
 * @method static Builder|Alert whereLogoColor($value)
 * @method static Builder|Alert whereMessage($value)
 * @method static Builder|Alert whereTextFontColor($value)
 * @method static Builder|Alert whereTextFontId($value)
 * @method static Builder|Alert whereTextFontSize($value)
 * @method static Builder|Alert whereTextStyleId($value)
 * @method static Builder|Alert whereTypeId($value)
 * @method static Builder|Alert whereUpdatedAt($value)
 * @method static Builder|Alert whereUserId($value)
 * @method static Builder|Alert whereUsername($value)
 * @method static Builder|Alert whereVolume($value)
 * @method static Builder|Alert whereWidgetUrl($value)
 * @method static Builder|Alert withTrashed()
 * @method static Builder|Alert withoutTrashed()
 * @mixin Eloquent
 */
class Alert extends Model
{
    use SoftDeletes;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'alerts';

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
        'user_id', 'type_id', 'animation_id', 'align_id', 'text_font_id',
        'text_style_id', 'logo_align_id', 'cover_id', 'duration', 'text_font_color',
        'text_font_size', 'logo_color', 'volume', 'username', 'amount',
        'action', 'message', 'widget_url'
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
    // Has one relations

    /**
     * @return HasOne
     */
    public function filter() : HasOne
    {
        return $this->hasOne(AlertProfanityFilter::class);
    }

    //--------------------------------------------------------------------------
    // Lists accessors

    /**
     * @return AlertTypeListItem|null
     */
    public function getType() : ?AlertTypeListItem
    {
        return AlertTypeList::getItem(
            $this->type_id
        );
    }

    /**
     * @return AlertAnimationListItem|null
     */
    public function getAnimation() : ?AlertAnimationListItem
    {
        return AlertAnimationList::getItem(
            $this->animation_id
        );
    }

    /**
     * @return AlertAlignListItem|null
     */
    public function getAlign() : ?AlertAlignListItem
    {
        return AlertAlignList::getItem(
            $this->align_id
        );
    }

    /**
     * @return AlertTextFontListItem|null
     */
    public function getTextFont() : ?AlertTextFontListItem
    {
        return AlertTextFontList::getItem(
            $this->text_font_id
        );
    }

    /**
     * @return AlertTextStyleListItem|null
     */
    public function getTextStyle() : ?AlertTextStyleListItem
    {
        return AlertTextStyleList::getItem(
            $this->text_style_id
        );
    }

    /**
     * @return AlertLogoAlignListItem|null
     */
    public function getLogoAlign() : ?AlertLogoAlignListItem
    {
        return AlertLogoAlignList::getItem(
            $this->logo_align_id
        );
    }

    /**
     * @return AlertCoverListItem|null
     */
    public function getCover() : ?AlertCoverListItem
    {
        return AlertCoverList::getItem(
            $this->cover_id
        );
    }
}
