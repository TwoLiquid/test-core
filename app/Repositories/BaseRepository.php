<?php

namespace App\Repositories;

/**
 * Class BaseRepository
 *
 * @package App\Repositories
 */
abstract class BaseRepository
{
    /**
     * Cache time
     *
     * @var int
     */
    protected int $cacheTime;

    /**
     * Pagination step
     *
     * @var int
     */
    protected int $perPage;

    /**
     * @return int
     */
    public function getCacheTime() : int
    {
        return $this->cacheTime;
    }

    /**
     * @param int $cacheTime
     */
    public function setCacheTime(
        int $cacheTime
    ) : void
    {
        $this->cacheTime = $cacheTime;
    }

    /**
     * @return int
     */
    public function getPerPage() : int
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage(
        int $perPage
    ) : void
    {
        $this->perPage = $perPage;
    }
}
