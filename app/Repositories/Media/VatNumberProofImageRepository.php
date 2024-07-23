<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MongoDb\User\Billing\VatNumberProof;
use App\Models\MySql\Media\VatNumberProofImage;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\VatNumberProofImageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class VatNumberProofImageRepository
 *
 * @package App\Repositories\Media
 */
class VatNumberProofImageRepository extends BaseRepository implements VatNumberProofImageRepositoryInterface
{
    /**
     * VatNumberProofImageRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vatNumberProofImage.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return VatNumberProofImage|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?VatNumberProofImage
    {
        try {
            return VatNumberProofImage::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vatNumberProofImage.' . __FUNCTION__),
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
            return VatNumberProofImage::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vatNumberProofImage.' . __FUNCTION__),
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
            return VatNumberProofImage::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vatNumberProofImage.' . __FUNCTION__),
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
            return VatNumberProofImage::query()
                ->where('vat_number_proof_id', '=', $vatNumberProof->_id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vatNumberProofImage.' . __FUNCTION__),
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
            return VatNumberProofImage::query()
                ->whereIn('vat_number_proof_id', $vatNumberProofs->pluck('_id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vatNumberProofImage.' . __FUNCTION__),
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
            return VatNumberProofImage::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/vatNumberProofImage.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}