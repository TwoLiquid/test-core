<?php

namespace App\Services\Admin;

use App\Models\MongoDb\User\Billing\VatNumberProof;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Role;
use App\Models\MySql\User\UserNote;
use App\Services\Admin\Interfaces\AdminServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AdminService
 *
 * @package App\Services\Admin
 */
class AdminService implements AdminServiceInterface
{
    /**
     * @param Collection $roles
     *
     * @return Collection
     */
    public function getByRoles(
        Collection $roles
    ) : Collection
    {
        $admins = new Collection();

        /** @var Role $role */
        foreach ($roles as $role) {
            if ($role->relationLoaded('admins')) {

                /** @var Admin $admin */
                foreach ($role->admins as $admin) {
                    $admins->push(
                        $admin
                    );
                }
            }
        }

        return $admins;
    }

    /**
     * @param Collection $vatNumberProofs
     *
     * @return Collection
     */
    public function getByVatNumberProofs(
        Collection $vatNumberProofs
    ) : Collection
    {
        $admins = new Collection();

        /** @var VatNumberProof $vatNumberProof */
        foreach ($vatNumberProofs as $vatNumberProof) {
            if ($vatNumberProof->relationLoaded('admin')) {
                $admins->push(
                    $vatNumberProof->admin
                );
            }

            if ($vatNumberProof->relationLoaded('admin')) {
                foreach ($vatNumberProof->excludeTaxHistory as $excludeTaxHistory) {
                    if ($excludeTaxHistory->relationLoaded('admin')) {
                        $admins->push(
                            $excludeTaxHistory->admin
                        );
                    }
                }
            }
        }

        return $admins;
    }

    /**
     * @param Collection $userNotes
     *
     * @return Collection
     */
    public function getByUserNotes(
        Collection $userNotes
    ) : Collection
    {
        $admins = new Collection();

        /** @var UserNote $userNote */
        foreach ($userNotes as $userNote) {
            if ($userNote->relationLoaded('admin')) {
                $admins->push(
                    $userNote->admin
                );
            }
        }

        return $admins;
    }
}
