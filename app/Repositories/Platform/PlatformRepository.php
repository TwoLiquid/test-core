<?php

namespace App\Repositories\Platform;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Platform;
use App\Repositories\BaseRepository;
use App\Repositories\Platform\Interfaces\PlatformRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class PlatformRepository
 *
 * @package App\Repositories\Platform
 */
class PlatformRepository extends BaseRepository implements PlatformRepositoryInterface
{
    /**
     * PlatformRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.platform.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Platform|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Platform
    {
        try {
            return Platform::query()
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return Platform|null
     *
     * @throws DatabaseException
     */
    public function findFullForAdminById(
        ?int $id
    ) : ?Platform
    {
        try {
            return Platform::query()
                ->with([
                    'vybes'
                ])
                ->withCount([
                    'vybes'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
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
            return Platform::query()
                ->withCount([
                    'vybes'
                ])
                ->orderBy('name')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return Platform::query()
                ->withCount([
                    'vybes'
                ])
                ->orderBy('name')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array|null $platformsIds
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByIds(
        ?array $platformsIds
    ) : Collection
    {
        try {
            return Platform::query()
                ->whereIn('id', $platformsIds)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Activity $activity
     * @param bool|null $voiceChat
     * @param bool|null $videoChat
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByActivity(
        Activity $activity,
        ?bool $voiceChat = null,
        ?bool $videoChat = null
    ) : Collection
    {
        try {
            return Platform::query()
                ->whereHas('activities', function ($query) use ($activity) {
                    $query->where('activity_id', $activity->id);
                })
                ->where('voice_chat', '=', $voiceChat)
                ->where('video_chat', '=', $videoChat)
                ->when($voiceChat, function ($query) use ($voiceChat) {
                    $query->where('voice_chat', '=', $voiceChat);
                })
                ->when($videoChat, function ($query) use ($videoChat) {
                    $query->where('video_chat', '=', $videoChat);
                })
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllForVoiceChat() : Collection
    {
        try {
            return Platform::query()
                ->where('voice_chat', '=', true)
                ->where('visible_in_voice_chat', '=', true)
                ->orderBy('name')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllForVideoChat() : Collection
    {
        try {
            return Platform::query()
                ->where('video_chat', '=', true)
                ->where('visible_in_video_chat', '=', true)
                ->orderBy('name')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $name
     * @param bool $voiceChat
     * @param bool $visibleInVoiceChat
     * @param bool $videoChat
     * @param bool $visibleInVideoChat
     *
     * @return Platform|null
     *
     * @throws DatabaseException
     */
    public function store(
        string $name,
        bool $voiceChat,
        bool $visibleInVoiceChat,
        bool $videoChat,
        bool $visibleInVideoChat
    ) : ?Platform
    {
        try {
            return Platform::query()->create([
                'name'                  => trim($name),
                'code'                  => generateCodeByName(trim($name)),
                'voice_chat'            => $voiceChat,
                'visible_in_voice_chat' => $visibleInVoiceChat,
                'video_chat'            => $videoChat,
                'visible_in_video_chat' => $visibleInVideoChat
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Platform $platform
     * @param string|null $name
     * @param bool|null $voiceChat
     * @param bool|null $visibleInVoiceChat
     * @param bool|null $videoChat
     * @param bool|null $visibleInVideoChat
     *
     * @return Platform
     *
     * @throws DatabaseException
     */
    public function update(
        Platform $platform,
        ?string $name,
        ?bool $voiceChat,
        ?bool $visibleInVoiceChat,
        ?bool $videoChat,
        ?bool $visibleInVideoChat
    ) : Platform
    {
        try {
            $platform->update([
                'name'                  => $name ? trim($name) : $platform->name,
                'code'                  => $name ? generateCodeFromName($name) : $platform->code,
                'voice_chat'            => !is_null($voiceChat) ? $voiceChat : $platform->voice_chat,
                'visible_in_voice_chat' => !is_null($visibleInVoiceChat) ? $visibleInVoiceChat : $platform->visible_in_voice_chat,
                'video_chat'            => !is_null($videoChat) ? $videoChat : $platform->video_chat,
                'visible_in_video_chat' => !is_null($visibleInVideoChat) ? $visibleInVideoChat : $platform->visible_in_video_chat
            ]);

            return $platform;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Platform $platform
     * @param Activity $activity
     *
     * @throws DatabaseException
     */
    public function attachActivity(
        Platform $platform,
        Activity $activity
    ) : void
    {
        try {
            $platform->activities()->sync([
                $activity->id
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Platform $platform
     * @param array $activitiesIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachActivities(
        Platform $platform,
        array $activitiesIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $platform->activities()->sync(
                $activitiesIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Platform $platform
     * @param Activity $activity
     *
     * @throws DatabaseException
     */
    public function detachActivity(
        Platform $platform,
        Activity $activity
    ) : void
    {
        try {
            $platform->activities()->detach([
                $activity->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Platform $platform
     * @param array $activitiesIds
     *
     * @throws DatabaseException
     */
    public function detachActivities(
        Platform $platform,
        array $activitiesIds
    ) : void
    {
        try {
            $platform->activities()->detach(
                $activitiesIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Platform $platform
     * @param AppearanceCase $appearanceCase
     *
     * @throws DatabaseException
     */
    public function attachAppearanceCase(
        Platform $platform,
        AppearanceCase $appearanceCase
    ) : void
    {
        try {
            $platform->appearanceCases()->sync([
                $appearanceCase->id
            ], false);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Platform $platform
     * @param array $appearanceCasesIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachAppearanceCases(
        Platform $platform,
        array $appearanceCasesIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $platform->appearanceCases()->sync(
                $appearanceCasesIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Platform $platform
     * @param AppearanceCase $appearanceCase
     *
     * @throws DatabaseException
     */
    public function detachAppearanceCase(
        Platform $platform,
        AppearanceCase $appearanceCase
    ) : void
    {
        try {
            $platform->appearanceCases()->detach([
                $appearanceCase->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Platform $platform
     * @param array $appearanceCasesIds
     *
     * @throws DatabaseException
     */
    public function detachAppearanceCases(
        Platform $platform,
        array $appearanceCasesIds
    ) : void
    {
        try {
            $platform->appearanceCases()->detach(
                $appearanceCasesIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Platform $platform
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Platform $platform
    ) : bool
    {
        try {
            return $platform->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/platform.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}