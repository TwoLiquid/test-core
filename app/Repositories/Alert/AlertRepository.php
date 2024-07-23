<?php

namespace App\Repositories\Alert;

use App\Exceptions\DatabaseException;
use App\Lists\Alert\Align\AlertAlignListItem;
use App\Lists\Alert\Animation\AlertAnimationListItem;
use App\Lists\Alert\Cover\AlertCoverListItem;
use App\Lists\Alert\Logo\Align\AlertLogoAlignListItem;
use App\Lists\Alert\Text\Font\AlertTextFontListItem;
use App\Lists\Alert\Text\Style\AlertTextStyleListItem;
use App\Lists\Alert\Type\AlertTypeListItem;
use App\Models\MySql\Alert\Alert;
use App\Models\MySql\User\User;
use App\Repositories\Alert\Interfaces\AlertRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class AlertRepository
 *
 * @package App\Repositories\Alert
 */
class AlertRepository extends BaseRepository implements AlertRepositoryInterface
{
    /**
     * AlertRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.alert.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Alert|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Alert
    {
        try {
            return Alert::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alert.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return Alert|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?int $id
    ) : ?Alert
    {
        try {
            return Alert::query()
                ->with([
                    'filter.words'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alert.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAll() : Collection
    {
        try {
            return Alert::query()
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alert.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Alert::query()
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alert.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllForUser(
        User $user
    ) : Collection
    {
        try {
            return Alert::query()
                ->with([
                    'filter.words'
                ])
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alert.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param AlertTypeListItem $alertTypeListItem
     * @param AlertAnimationListItem $alertAnimationListItem
     * @param AlertAlignListItem $alertAlignListItem
     * @param AlertTextFontListItem $alertTextFontListItem
     * @param AlertTextStyleListItem $alertTextStyleListItem
     * @param AlertLogoAlignListItem $alertLogoAlignListItem
     * @param AlertCoverListItem $alertCoverListItem
     * @param int $duration
     * @param string $textFontColor
     * @param int $textFontSize
     * @param string $logoColor
     * @param int $volume
     * @param string|null $username
     * @param float|null $amount
     * @param string|null $action
     * @param string|null $message
     * @param string|null $widgetUrl
     *
     * @return Alert|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        AlertTypeListItem $alertTypeListItem,
        AlertAnimationListItem $alertAnimationListItem,
        AlertAlignListItem $alertAlignListItem,
        AlertTextFontListItem $alertTextFontListItem,
        AlertTextStyleListItem $alertTextStyleListItem,
        AlertLogoAlignListItem $alertLogoAlignListItem,
        AlertCoverListItem $alertCoverListItem,
        int $duration,
        string $textFontColor,
        int $textFontSize,
        string $logoColor,
        int $volume,
        ?string $username,
        ?float $amount,
        ?string $action,
        ?string $message,
        ?string $widgetUrl
    ) : ?Alert
    {
        try {
            return Alert::query()->create([
                'user_id'         => $user->id,
                'type_id'         => $alertTypeListItem->id,
                'animation_id'    => $alertAnimationListItem->id,
                'align_id'        => $alertAlignListItem->id,
                'text_font_id'    => $alertTextFontListItem->id,
                'text_style_id'   => $alertTextStyleListItem->id,
                'logo_align_id'   => $alertLogoAlignListItem->id,
                'cover_id'        => $alertCoverListItem->id,
                'duration'        => $duration,
                'text_font_color' => trim($textFontColor),
                'text_font_size'  => $textFontSize,
                'logo_color'      => trim($logoColor),
                'volume'          => $volume,
                'username'        => $username ? trim($username) : null,
                'amount'          => $amount,
                'action'          => $action ? trim($action) : null,
                'message'         => $message ? trim($message) : null,
                'widget_url'      => $widgetUrl ? trim($widgetUrl) : null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alert.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Alert $alert
     * @param User|null $user
     * @param AlertTypeListItem|null $alertTypeListItem
     * @param AlertAnimationListItem|null $alertAnimationListItem
     * @param AlertAlignListItem|null $alertAlignListItem
     * @param AlertTextFontListItem|null $alertTextFontListItem
     * @param AlertTextStyleListItem|null $alertTextStyleListItem
     * @param AlertLogoAlignListItem|null $alertLogoAlignListItem
     * @param AlertCoverListItem|null $alertCoverListItem
     * @param int|null $duration
     * @param string|null $textFontColor
     * @param int|null $textFontSize
     * @param string|null $logoColor
     * @param int|null $volume
     * @param string|null $username
     * @param string|null $amount
     * @param string|null $action
     * @param string|null $message
     * @param string|null $widgetUrl
     *
     * @return Alert
     *
     * @throws DatabaseException
     */
    public function update(
        Alert $alert,
        ?User $user,
        ?AlertTypeListItem $alertTypeListItem,
        ?AlertAnimationListItem $alertAnimationListItem,
        ?AlertAlignListItem $alertAlignListItem,
        ?AlertTextFontListItem $alertTextFontListItem,
        ?AlertTextStyleListItem $alertTextStyleListItem,
        ?AlertLogoAlignListItem $alertLogoAlignListItem,
        ?AlertCoverListItem $alertCoverListItem,
        ?int $duration,
        ?string $textFontColor,
        ?int $textFontSize,
        ?string $logoColor,
        ?int $volume,
        ?string $username,
        ?string $amount,
        ?string $action,
        ?string $message,
        ?string $widgetUrl
    ) : Alert
    {
        try {
            $alert->update([
                'user_id'         => $user ? $user->id : $alert->user_id,
                'type_id'         => $alertTypeListItem ? $alertTypeListItem->id : $alert->type_id,
                'animation_id'    => $alertAnimationListItem ? $alertAnimationListItem->id  : $alert->animation_id,
                'align_id'        => $alertAlignListItem ? $alertAlignListItem->id : $alert->align_id,
                'text_font_id'    => $alertTextFontListItem ? $alertTextFontListItem->id : $alert->text_font_id,
                'text_style_id'   => $alertTextStyleListItem ? $alertTextStyleListItem->id : $alert->text_style_id,
                'logo_align_id'   => $alertLogoAlignListItem? $alertLogoAlignListItem->id : $alert->logo_align_id,
                'cover_id'        => $alertCoverListItem ? $alertCoverListItem->id : $alert->cover_id,
                'duration'        => $duration,
                'text_font_color' => trim($textFontColor),
                'text_font_size'  => $textFontSize,
                'logo_color'      => trim($logoColor),
                'volume'          => $volume,
                'username'        => $username ? trim($username) : $alert->username,
                'amount'          => $amount ?: $alert->amount,
                'action'          => $action ? trim($action) : $alert->action,
                'message'         => $message ? trim($message) : $alert->message,
                'widget_url'      => $widgetUrl ? trim($widgetUrl) : null
            ]);

            return $alert;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alert.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Alert $alert
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Alert $alert
    ) : bool
    {
        try {
            return $alert->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alert.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}