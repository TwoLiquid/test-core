<?php

namespace App\Services\File\Interfaces;

/**
 * Interface FileServiceInterface
 *
 * @package App\Services\File\Interfaces
 */
interface FileServiceInterface
{
    /**
     * This method provides getting data
     * by related file data
     *
     * @param array $files
     *
     * @return array
     */
    public function sortFiles(
        array $files
    ) : array;
}