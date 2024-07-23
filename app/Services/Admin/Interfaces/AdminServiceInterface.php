<?php

namespace App\Services\Admin\Interfaces;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface AdminServiceInterface
 *
 * @package App\Services\Admin\Interfaces
 */
interface AdminServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param Collection $roles
     *
     * @return Collection
     */
    public function getByRoles(
        Collection $roles
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $vatNumberProofs
     *
     * @return Collection
     */
    public function getByVatNumberProofs(
        Collection $vatNumberProofs
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $userNotes
     *
     * @return Collection
     */
    public function getByUserNotes(
        Collection $userNotes
    ) : Collection;
}
