<?php

namespace App\Repositories\VatNumberProof;

use App\Exceptions\DatabaseException;
use App\Lists\VatNumberProof\Status\VatNumberProofStatusList;
use App\Lists\VatNumberProof\Status\VatNumberProofStatusListItem;
use App\Models\MongoDb\User\Billing\VatNumberProof;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\Billing;
use App\Models\MySql\Place\CountryPlace;
use App\Repositories\BaseRepository;
use App\Repositories\VatNumberProof\Interfaces\VatNumberProofRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class VatNumberProofRepository
 *
 * @package App\Repositories\VatNumberProof
 */
class VatNumberProofRepository extends BaseRepository implements VatNumberProofRepositoryInterface
{
    /**
     * VatNumberProofRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vatNumberProof.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return VatNumberProof|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?VatNumberProof
    {
        try {
            return VatNumberProof::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vatNumberProof.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string|null $id
     *
     * @return VatNumberProof|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?string $id
    ) : ?VatNumberProof
    {
        try {
            return VatNumberProof::query()
                ->with([
                    'admin',
                    'countryPlace',
                    'excludeTaxHistory.admin'
                ])
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vatNumberProof.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Billing $billing
     *
     * @return VatNumberProof|null
     *
     * @throws DatabaseException
     */
    public function findByBilling(
        Billing $billing
    ) : ?VatNumberProof
    {
        try {
            return VatNumberProof::query()
                ->with([
                    'countryPlace'
                ])
                ->where('country_place_id', '=', $billing->country_place_id)
                ->where('company_name', '=', $billing->company_name)
                ->where('vat_number', '=', $billing->vat_number)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vatNumberProof.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAll() : Collection
    {
        try {
            return VatNumberProof::query()
                ->with([
                    'country'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vatNumberProof.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return VatNumberProof::query()
                ->with([
                    'country'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vatNumberProof.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Billing $billing
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByBilling(
        Billing $billing
    ) : Collection
    {
        try {
            return VatNumberProof::query()
                ->with([
                    'admin',
                    'countryPlace',
                    'excludeTaxHistory.admin'
                ])
                ->where('billing_id', '=', $billing->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vatNumberProof.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Billing $billing
     * @param CountryPlace $countryPlace
     * @param string $companyName
     * @param string $vatNumber
     * @param Admin $admin
     *
     * @return VatNumberProof|null
     *
     * @throws DatabaseException
     */
    public function store(
        Billing $billing,
        CountryPlace $countryPlace,
        string $companyName,
        string $vatNumber,
        Admin $admin
    ) : ?VatNumberProof
    {
        try {
            return VatNumberProof::query()->create([
                'billing_id'         => $billing->id,
                'country_place_id'   => $countryPlace->place_id,
                'company_name'       => $companyName,
                'vat_number'         => $vatNumber,
                'exclude_tax'        => false,
                'status_id'          => VatNumberProofStatusList::getInactiveItem()->id,
                'admin_id'           => $admin->id,
                'action_date'        => Carbon::now()->format('Y-m-d'),
                'exclude_tax_date'   => null,
                'status_change_date' => null
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vatNumberProof.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VatNumberProof $vatNumberProof
     * @param VatNumberProofStatusListItem|null $vatNumberProofStatusListItem
     * @param bool|null $excludeTax
     * @param string|null $excludeTaxDate
     * @param string|null $statusChangeDate
     *
     * @return VatNumberProof|null
     *
     * @throws DatabaseException
     */
    public function update(
        VatNumberProof $vatNumberProof,
        ?VatNumberProofStatusListItem $vatNumberProofStatusListItem,
        ?bool $excludeTax,
        ?string $excludeTaxDate,
        ?string $statusChangeDate
    ) : ?VatNumberProof
    {
        try {
            $vatNumberProof->update([
                'status_id'          => $vatNumberProofStatusListItem?->id,
                'exclude_tax'        => $excludeTax,
                'exclude_tax_date'   => $excludeTaxDate ? Carbon::parse($excludeTaxDate)->format('Y-m-d') : null,
                'status_change_date' => $statusChangeDate ? Carbon::parse($statusChangeDate)->format('Y-m-d') : null
            ]);

            return $vatNumberProof;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vatNumberProof.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VatNumberProof $vatNumberProof
     * @param VatNumberProofStatusListItem $vatNumberProofStatusListItem
     *
     * @return VatNumberProof|null
     *
     * @throws DatabaseException
     */
    public function updateStatus(
        VatNumberProof $vatNumberProof,
        VatNumberProofStatusListItem $vatNumberProofStatusListItem
    ) : ?VatNumberProof
    {
        try {
            $vatNumberProof->update([
                'status_id'          => $vatNumberProofStatusListItem->id,
                'status_change_date' => Carbon::now()->format('Y-m-d')
            ]);

            return $vatNumberProof;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vatNumberProof.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VatNumberProof $vatNumberProof
     * @param bool $excludeTax
     *
     * @return VatNumberProof|null
     *
     * @throws DatabaseException
     */
    public function updateExcludeTax(
        VatNumberProof $vatNumberProof,
        bool $excludeTax
    ) : ?VatNumberProof
    {
        try {
            $vatNumberProof->update([
                'exclude_tax'      => $excludeTax,
                'exclude_tax_date' => Carbon::now()->format('Y-m-d')
            ]);

            return $vatNumberProof;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vatNumberProof.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VatNumberProof $vatNumberProof
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        VatNumberProof $vatNumberProof
    ) : bool
    {
        try {
            return $vatNumberProof->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vatNumberProof.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
