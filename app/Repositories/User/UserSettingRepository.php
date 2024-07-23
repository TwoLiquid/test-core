<?php

namespace App\Repositories\User;

use App\Exceptions\DatabaseException;
use App\Lists\Setting\Type\SettingTypeList;
use App\Lists\Setting\Type\SettingTypeListItem;
use App\Models\MySql\User\User;
use App\Models\MySql\User\UserSetting;
use App\Repositories\BaseRepository;
use App\Repositories\User\Interfaces\UserSettingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class UserSettingRepository
 *
 * @package App\Repositories\User
 */
class UserSettingRepository extends BaseRepository implements UserSettingRepositoryInterface
{
    /**
     * UserSettingRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.userSetting.perPage');
    }

    /**
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return UserSetting|null
     *
     * @throws DatabaseException
     */
    public function findByCodeDefault(
        string $blockCode,
        string $settingCode
    ) : ?UserSetting
    {
        try {
            return UserSetting::query()
                ->where('type_id', '=', SettingTypeList::getDefault()->id)
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return UserSetting|null
     *
     * @throws DatabaseException
     */
    public function findByCodeCustom(
        User $user,
        string $blockCode,
        string $settingCode
    ) : ?UserSetting
    {
        try {
            return UserSetting::query()
                ->where('type_id', '=', SettingTypeList::getCustom()->id)
                ->where('user_id', '=', $user->id)
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $blockCode
     * @param array $settingsCodes
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByCodesDefault(
        string $blockCode,
        array $settingsCodes
    ) : Collection
    {
        try {
            return UserSetting::query()
                ->where('type_id', '=', SettingTypeList::getDefault()->id)
                ->where('block_code', '=', $blockCode)
                ->whereIn('setting_code', $settingsCodes)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string $blockCode
     * @param array $settingsCodes
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByCodesCustom(
        User $user,
        string $blockCode,
        array $settingsCodes
    ) : Collection
    {
        try {
            return UserSetting::query()
                ->where('type_id', '=', SettingTypeList::getCustom()->id)
                ->where('user_id', '=', $user->id)
                ->where('block_code', '=', $blockCode)
                ->whereIn('setting_code', $settingsCodes)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function existsByCodeDefault(
        string $blockCode,
        string $settingCode
    ) : bool
    {
        try {
            return UserSetting::query()
                ->where('type_id', '=', SettingTypeList::getDefault()->id)
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param SettingTypeListItem $settingTypeListItem
     * @param User|null $user
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     * @param bool $active
     *
     * @return UserSetting|null
     *
     * @throws DatabaseException
     */
    public function store(
        SettingTypeListItem $settingTypeListItem,
        ?User $user,
        string $blockCode,
        string $settingCode,
        $value,
        bool $active
    ) : ?UserSetting
    {
        try {
            return UserSetting::query()->create([
                'type_id'      => $settingTypeListItem->id,
                'user_id'      => $user?->id,
                'block_code'   => $blockCode,
                'setting_code' => $settingCode,
                'value'        => $value,
                'active'       => $active
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     *
     * @return UserSetting|null
     *
     * @throws DatabaseException
     */
    public function updateValueByCodeDefault(
        string $blockCode,
        string $settingCode,
        $value
    ) : ?UserSetting
    {
        try {
            $userSetting = UserSetting::query()
                ->where('type_id', '=', SettingTypeList::getDefault()->id)
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->first();

            $userSetting?->update([
                'value' => $value
            ]);
            
            return $userSetting;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     * @param bool|null $active
     *
     * @return UserSetting|null
     *
     * @throws DatabaseException
     */
    public function updateValueByCodeCustom(
        User $user,
        string $blockCode,
        string $settingCode,
        $value,
        ?bool $active = null
    ) : ?UserSetting
    {
        try {
            $userSetting = UserSetting::query()
                ->where('type_id', '=', SettingTypeList::getCustom()->id)
                ->where('user_id', '=', $user->id)
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->first();

            $userSetting?->update([
                'value' => $value,
                'active' => !is_null($active) ? $active : $userSetting->active
            ]);

            return $userSetting;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     * @param bool|null $active
     *
     * @return UserSetting|null
     *
     * @throws DatabaseException
     */
    public function updateOrCreateValueByCodeCustom(
        User $user,
        string $blockCode,
        string $settingCode,
        $value,
        ?bool $active = null
    ) : ?UserSetting
    {
        try {
            $userSetting = UserSetting::query()
                ->updateOrCreate([
                    'type_id'      => SettingTypeList::getCustom()->id,
                    'user_id'      => $user->id,
                    'block_code'   => $blockCode,
                    'setting_code' => $settingCode
                ], [
                    'value' => $value
                ]);
            if ($userSetting && !is_null($active)) {
                $userSetting->update([
                    'active' => $active
                ]);
            }

            return $userSetting;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteByCodeCustom(
        User $user,
        string $blockCode,
        string $settingCode
    ) : bool
    {
        try {
            return UserSetting::query()
                ->where('type_id', '=', SettingTypeList::getCustom()->id)
                ->where('user_id', '=', $user->id)
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}