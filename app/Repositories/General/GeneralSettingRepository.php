<?php

namespace App\Repositories\General;

use App\Exceptions\DatabaseException;
use App\Models\MySql\GeneralSetting;
use App\Repositories\BaseRepository;
use App\Repositories\General\Interfaces\GeneralSettingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class GeneralSettingRepository
 *
 * @package App\Repositories\General
 */
class GeneralSettingRepository extends BaseRepository implements GeneralSettingRepositoryInterface
{
    /**
     * RequestSettingRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.generalSetting.perPage');
    }

    /**
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return GeneralSetting|null
     *
     * @throws DatabaseException
     */
    public function findByCode(
        string $blockCode,
        string $settingCode
    ) : ?GeneralSetting
    {
        try {
            return GeneralSetting::query()
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/generalSetting.' . __FUNCTION__),
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
    public function getByCodes(
        string $blockCode,
        array $settingsCodes
    ) : Collection
    {
        try {
            return GeneralSetting::query()
                ->where('block_code', '=', $blockCode)
                ->whereIn('setting_code', $settingsCodes)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/generalSetting.' . __FUNCTION__),
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
    public function existsByCode(
        string $blockCode,
        string $settingCode
    ) : bool
    {
        try {
            return GeneralSetting::query()
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/generalSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     *
     * @return GeneralSetting|null
     *
     * @throws DatabaseException
     */
    public function store(
        string $blockCode,
        string $settingCode,
        $value
    ) : ?GeneralSetting
    {
        try {
            return GeneralSetting::query()->create([
                'block_code'   => $blockCode,
                'setting_code' => $settingCode,
                'value'        => $value
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/generalSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     *
     * @return GeneralSetting|null
     *
     * @throws DatabaseException
     */
    public function updateValueByCode(
        string $blockCode,
        string $settingCode,
        $value
    ) : ?GeneralSetting
    {
        try {
            $generalSetting = GeneralSetting::query()
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->first();

            $generalSetting?->update([
                'value' => $value
            ]);
            
            return $generalSetting;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/generalSetting.' . __FUNCTION__),
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
    public function deleteByCode(
        string $blockCode,
        string $settingCode
    ) : bool
    {
        try {
            return GeneralSetting::query()
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/generalSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}