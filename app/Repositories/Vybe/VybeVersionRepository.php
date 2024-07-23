<?php

namespace App\Repositories\Vybe;

use App\Exceptions\DatabaseException;
use App\Models\MongoDb\Vybe\VybeVersion;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\BaseRepository;
use App\Repositories\Vybe\Interfaces\VybeVersionRepositoryInterface;
use Exception;

/**
 * Class VybeVersionRepository
 *
 * @package App\Repositories\Vybe
 */
class VybeVersionRepository extends BaseRepository implements VybeVersionRepositoryInterface
{
    /**
     * VybeRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vybeVersion.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return VybeVersion|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?VybeVersion
    {
        try {
            return VybeVersion::query()
                ->where('_id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeVersion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param int $number
     *
     * @return VybeVersion|null
     *
     * @throws DatabaseException
     */
    public function findByNumber(
        Vybe $vybe,
        int $number
    ) : ?VybeVersion
    {
        try {
            return VybeVersion::query()
                ->where('vybe_id', '=', $vybe->id)
                ->where('number', '=', $number)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeVersion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param int $number
     * @param string $type
     * @param string $title
     * @param string $category
     * @param string|null $subcategory
     * @param string $activity
     * @param array|null $devices
     * @param int|null $vybeHandlingFee
     * @param string $period
     * @param int $userCount
     * @param array $appearanceCases
     * @param array $schedules
     * @param int|null $orderAdvance
     * @param array|null $imagesIds
     * @param array|null $videosIds
     * @param string $access
     * @param string $showcase
     * @param string $orderAccept
     * @param string $ageLimit
     * @param string $status
     *
     * @return VybeVersion|null
     *
     * @throws DatabaseException
     */
    public function store(
        Vybe $vybe,
        int $number,
        string $type,
        string $title,
        string $category,
        ?string $subcategory,
        string $activity,
        ?array $devices,
        ?int $vybeHandlingFee,
        string $period,
        int $userCount,
        array $appearanceCases,
        array $schedules,
        ?int $orderAdvance,
        ?array $imagesIds,
        ?array $videosIds,
        string $access,
        string $showcase,
        string $orderAccept,
        string $ageLimit,
        string $status
    ) : ?VybeVersion
    {
        try {
            return VybeVersion::query()->create([
                'vybe_id'           => $vybe->id,
                'number'            => $number,
                'type'              => $type,
                'title'             => $title,
                'category'          => $category,
                'subcategory'       => $subcategory,
                'activity'          => $activity,
                'devices'           => $devices,
                'vybe_handling_fee' => $vybeHandlingFee,
                'period'            => $period,
                'user_count'        => $userCount,
                'appearance_cases'  => $appearanceCases,
                'schedules'         => $schedules,
                'order_advance'     => $orderAdvance,
                'images_ids'        => $imagesIds,
                'videos_ids'        => $videosIds,
                'access'            => $access,
                'showcase'          => $showcase,
                'order_accept'      => $orderAccept,
                'age_limit'         => $ageLimit,
                'status'            => $status
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeVersion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeVersion $vybeVersion
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        VybeVersion $vybeVersion
    ) : bool
    {
        try {
            return $vybeVersion->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeVersion.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
