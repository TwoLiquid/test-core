<?php

namespace App\Services\Request\Interfaces;

use App\Lists\Request\Group\RequestGroupListItem;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface RequestServiceInterface
 *
 * @package App\Services\Request\Interfaces
 */
interface RequestServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param RequestGroupListItem|null $requestGroupListItem
     *
     * @return Collection
     */
    public function getRequestTypesWithCounts(
        ?RequestGroupListItem $requestGroupListItem
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @return Collection
     */
    public function getRequestGroupsWithCounts() : Collection;

    /**
     * This method provides getting data
     *
     * @return Collection
     */
    public function getUserRequestsCounts() : Collection;

    /**
     * This method provides getting data
     *
     * @return Collection
     */
    public function getFinanceRequestsCounts() : Collection;

    /**
     * This method provides getting data
     *
     * @return Collection
     */
    public function getVybeRequestsCounts() : Collection;

    /**
     * This method provides getting data
     *
     * @return int
     */
    public function getUserRequestsTotalCount() : int;

    /**
     * This method provides getting data
     *
     * @return int
     */
    public function getFinanceRequestsTotalCount() : int;

    /**
     * This method provides getting data
     *
     * @return int
     */
    public function getVybeRequestsTotalCount() : int;
}