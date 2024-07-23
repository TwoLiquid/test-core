<?php

namespace App\Repositories\Vybe\Interfaces;

use App\Models\MongoDb\Vybe\VybeVersion;
use App\Models\MySql\Vybe\Vybe;

/**
 * Interface VybeVersionRepositoryInterface
 *
 * @package App\Repositories\Vybe\Interfaces
 */
interface VybeVersionRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VybeVersion|null
     */
    public function findById(
        ?string $id
    ) : ?VybeVersion;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param Vybe $vybe
     * @param int $number
     *
     * @return VybeVersion|null
     */
    public function findByNumber(
        Vybe $vybe,
        int $number
    ) : ?VybeVersion;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
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
    ) : ?VybeVersion;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybeVersion $vybeVersion
     *
     * @return bool
     */
    public function delete(
        VybeVersion $vybeVersion
    ) : bool;
}
