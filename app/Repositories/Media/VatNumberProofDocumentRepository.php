<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MongoDb\User\Billing\VatNumberProof;
use App\Models\MySql\Media\VatNumberProofDocument;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\VatNumberProofDocumentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class VatNumberProofDocumentRepository
 *
 * @package App\Repositories\Media
 */
class VatNumberProofDocumentRepository extends BaseRepository implements VatNumberProofDocumentRepositoryInterface
{
    /**
     * VatNumberProofDocumentRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vatNumberProofDocument.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return VatNumberProofDocument|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?VatNumberProofDocument
    {
        try {
            return VatNumberProofDocument::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vatNumberProofDocument.' . __FUNCTION__),
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
            return VatNumberProofDocument::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vatNumberProofDocument.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return VatNumberProofDocument::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vatNumberProofDocument.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VatNumberProof $vatNumberProof
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByVatNumberProof(
        VatNumberProof $vatNumberProof
    ) : Collection
    {
        try {
            return VatNumberProofDocument::query()
                ->where('vat_number_proof_id', '=', $vatNumberProof->_id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vatNumberProofDocument.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $vatNumberProofs
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByVatNumberProofs(
        Collection $vatNumberProofs
    ) : Collection
    {
        try {
            return VatNumberProofDocument::query()
                ->whereIn('vat_number_proof_id', $vatNumberProofs->pluck('_id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vatNumberProofDocument.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $ids
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByIds(
        array $ids
    ) : Collection
    {
        try {
            return VatNumberProofDocument::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vatNumberProofDocument.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}