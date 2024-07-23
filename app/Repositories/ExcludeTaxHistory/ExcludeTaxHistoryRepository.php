<?php

namespace App\Repositories\ExcludeTaxHistory;

use App\Exceptions\DatabaseException;
use App\Models\MongoDb\User\Billing\ExcludeTaxHistory;
use App\Models\MongoDb\User\Billing\VatNumberProof;
use App\Models\MySql\Admin\Admin;
use App\Repositories\BaseRepository;
use App\Repositories\ExcludeTaxHistory\Interfaces\ExcludeTaxHistoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class ExcludeTaxHistoryRepository
 *
 * @package App\Repositories\ExcludeTaxHistory
 */
class ExcludeTaxHistoryRepository extends BaseRepository implements ExcludeTaxHistoryRepositoryInterface
{
    /**
     * ExcludeTaxHistoryRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.excludeTaxHistory.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return ExcludeTaxHistory|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?ExcludeTaxHistory
    {
        try {
            return ExcludeTaxHistory::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/excludeTaxHistory.' . __FUNCTION__),
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
            return ExcludeTaxHistory::query()
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/excludeTaxHistory.' . __FUNCTION__),
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
            return ExcludeTaxHistory::query()
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/excludeTaxHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VatNumberProof $vatNumberProof
     * @param Admin $admin
     * @param bool $value
     *
     * @return ExcludeTaxHistory|null
     *
     * @throws DatabaseException
     */
    public function store(
        VatNumberProof $vatNumberProof,
        Admin $admin,
        bool $value
    ) : ?ExcludeTaxHistory
    {
        try {
            return ExcludeTaxHistory::query()->create([
                'vat_number_proof_id' => $vatNumberProof->_id,
                'admin_id'            => $admin->id,
                'value'               => $value
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/excludeTaxHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param ExcludeTaxHistory $excludeTaxHistory
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        ExcludeTaxHistory $excludeTaxHistory
    ) : bool
    {
        try {
            return $excludeTaxHistory->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/excludeTaxHistory.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
