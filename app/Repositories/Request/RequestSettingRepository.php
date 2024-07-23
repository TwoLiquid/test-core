<?php

namespace App\Repositories\Request;

use App\Exceptions\DatabaseException;
use App\Models\MySql\RequestSetting;
use App\Repositories\BaseRepository;
use App\Repositories\Request\Interfaces\RequestSettingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class RequestSettingRepository
 *
 * @package App\Repositories\Request
 */
class RequestSettingRepository extends BaseRepository implements RequestSettingRepositoryInterface
{
    /**
     * RequestSettingRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.requestSetting.perPage');
    }

    /**
     * @param string $blockCode
     * @param string $settingCode
     *
     * @return RequestSetting|null
     *
     * @throws DatabaseException
     */
    public function findByCode(
        string $blockCode,
        string $settingCode
    ) : ?RequestSetting
    {
        try {
            return RequestSetting::query()
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/requestSetting.' . __FUNCTION__),
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
            return RequestSetting::query()
                ->where('block_code', '=', $blockCode)
                ->whereIn('setting_code', $settingsCodes)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/requestSetting.' . __FUNCTION__),
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
            return RequestSetting::query()
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/requestSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     *
     * @return RequestSetting|null
     *
     * @throws DatabaseException
     */
    public function store(
        string $blockCode,
        string $settingCode,
        $value
    ) : ?RequestSetting
    {
        try {
            return RequestSetting::query()->create([
                'block_code'   => $blockCode,
                'setting_code' => $settingCode,
                'value'        => $value
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/requestSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $blockCode
     * @param string $settingCode
     * @param $value
     *
     * @return RequestSetting|null
     *
     * @throws DatabaseException
     */
    public function updateValueByCode(
        string $blockCode,
        string $settingCode,
        $value
    ) : ?RequestSetting
    {
        try {
            $requestSetting = RequestSetting::query()
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->first();

            $requestSetting?->update([
                'value' => $value
            ]);
            
            return $requestSetting;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/requestSetting.' . __FUNCTION__),
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
            return RequestSetting::query()
                ->where('block_code', '=', $blockCode)
                ->where('setting_code', '=', $settingCode)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/requestSetting.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}