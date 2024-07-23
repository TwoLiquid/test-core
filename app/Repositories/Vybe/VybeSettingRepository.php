<?php

namespace App\Repositories\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Setting\Type\SettingTypeList;
use App\Lists\Setting\Type\SettingTypeListItem;
use App\Models\MySql\Vybe\Vybe;
use App\Models\MySql\Vybe\VybeSetting;
use App\Repositories\BaseRepository;
use App\Repositories\Vybe\Interfaces\VybeSettingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class VybeSettingRepository
 *
 * @package App\Repositories\Vybe
 */
class VybeSettingRepository extends BaseRepository implements VybeSettingRepositoryInterface
{
    /**
     * VybeSettingRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vybeSetting.perPage');
    }

    /**
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return VybeSetting|null
     *
     * @throws DatabaseException
     */
    public function findByCodeDefault(
        string $blockCode,
        string $settingCode
    ) : ?VybeSetting
    {
        try {
            return VybeSetting::query()
                ->where('type_id', '=', SettingTypeList::getDefault()->id)
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return VybeSetting|null
     *
     * @throws DatabaseException
     */
    public function findByCodeCustom(
        Vybe $vybe,
        string $blockCode,
        string $settingCode
    ) : ?VybeSetting
    {
        try {
            return VybeSetting::query()
                ->where('type_id', '=', SettingTypeList::getCustom()->id)
                ->where('vybe_id', '=', $vybe->id)
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeSetting.' . __FUNCTION__),
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
            return VybeSetting::query()
                ->where('type_id', '=', SettingTypeList::getDefault()->id)
                ->where('block_code', '=', $blockCode)
                ->whereIn('setting_code', $settingsCodes)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param string $blockCode
     * @param array $settingsCodes
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByCodesCustom(
        Vybe $vybe,
        string $blockCode,
        array $settingsCodes
    ) : Collection
    {
        try {
            return VybeSetting::query()
                ->where('type_id', '=', SettingTypeList::getCustom()->id)
                ->where('vybe_id', '=', $vybe->id)
                ->where('block_code', '=', $blockCode)
                ->whereIn('setting_code', $settingsCodes)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeSetting.' . __FUNCTION__),
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
            return VybeSetting::query()
                ->where('type_id', '=', SettingTypeList::getDefault()->id)
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function existsForVybeByCodeCustom(
        Vybe $vybe,
        string $blockCode,
        string $settingCode
    ) : bool
    {
        try {
            return VybeSetting::query()
                ->where('type_id', '=', SettingTypeList::getCustom()->id)
                ->where('vybe_id', '=', $vybe->id)
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param SettingTypeListItem $settingTypeListItem
     * @param Vybe|null $vybe
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     * @param bool $active
     *
     * @return VybeSetting|null
     *
     * @throws DatabaseException
     */
    public function store(
        SettingTypeListItem $settingTypeListItem,
        ?Vybe $vybe,
        string $blockCode,
        string $settingCode,
        $value,
        bool $active
    ) : ?VybeSetting
    {
        try {
            return VybeSetting::query()->create([
                'type_id'      => $settingTypeListItem->id,
                'vybe_id'      => $vybe?->id,
                'block_code'   => $blockCode,
                'setting_code' => $settingCode,
                'value'        => $value,
                'active'       => $active
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     *
     * @return VybeSetting|null
     *
     * @throws DatabaseException
     */
    public function updateValueByCodeDefault(
        string $blockCode,
        string $settingCode,
        $value
    ) : ?VybeSetting
    {
        try {
            $vybeSetting = VybeSetting::query()
                ->where('type_id', '=', SettingTypeList::getDefault()->id)
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->first();

            $vybeSetting?->update([
                'value' => $value
            ]);

            return $vybeSetting;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     * @param bool|null $active
     *
     * @return VybeSetting|null
     *
     * @throws DatabaseException
     */
    public function updateValueByCodeCustom(
        Vybe $vybe,
        string $blockCode,
        string $settingCode,
        $value,
        ?bool $active = null
    ) : ?VybeSetting
    {
        try {
            $vybeSetting = VybeSetting::query()
                ->where('type_id', '=', SettingTypeList::getCustom()->id)
                ->where('vybe_id', '=', $vybe->id)
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->first();

            $vybeSetting?->update([
                'value' => $value,
                'active' => !is_null($active) ? $active : $vybeSetting->active
            ]);

            return $vybeSetting;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     * @param bool|null $active
     *
     * @return VybeSetting|null
     *
     * @throws DatabaseException
     */
    public function updateOrCreateValueByCodeCustom(
        Vybe $vybe,
        string $blockCode,
        string $settingCode,
        $value,
        ?bool $active = null
    ) : ?VybeSetting
    {
        try {
            $vybeSetting = VybeSetting::query()
                ->updateOrCreate([
                    'type_id'      => SettingTypeList::getCustom()->id,
                    'vybe_id'      => $vybe->id,
                    'block_code'   => $blockCode,
                    'setting_code' => $settingCode
                ], [
                    'value' => $value
                ]);

            if ($vybeSetting && !is_null($active)) {
                $vybeSetting->update([
                    'active' => $active
                ]);
            }

            return $vybeSetting;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteByCodeCustom(
        Vybe $vybe,
        string $blockCode,
        string $settingCode
    ) : bool
    {
        try {
            return VybeSetting::query()
                ->where('type_id', '=', SettingTypeList::getCustom()->id)
                ->where('vybe_id', '=', $vybe->id)
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
