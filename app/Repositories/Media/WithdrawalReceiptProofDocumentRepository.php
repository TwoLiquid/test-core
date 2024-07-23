<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Media\VatNumberProofDocument;
use App\Models\MySql\Media\WithdrawalReceiptProofDocument;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\WithdrawalReceiptProofDocumentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class WithdrawalReceiptProofDocumentRepository
 *
 * @package App\Repositories\Media
 */
class WithdrawalReceiptProofDocumentRepository extends BaseRepository implements WithdrawalReceiptProofDocumentRepositoryInterface
{
    /**
     * WithdrawalReceiptProofDocumentRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.withdrawalReceiptProofDocument.perPage');
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
    ) : ?WithdrawalReceiptProofDocument
    {
        try {
            return WithdrawalReceiptProofDocument::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/withdrawalReceiptProofDocument.' . __FUNCTION__),
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
            return WithdrawalReceiptProofDocument::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/withdrawalReceiptProofDocument.' . __FUNCTION__),
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
            return WithdrawalReceiptProofDocument::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/withdrawalReceiptProofDocument.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByReceipt(
        WithdrawalReceipt $withdrawalReceipt
    ) : Collection
    {
        try {
            return WithdrawalReceiptProofDocument::query()
                ->where('receipt_id', '=', $withdrawalReceipt->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/withdrawalReceiptProofDocument.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $withdrawalReceipts
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByReceipts(
        Collection $withdrawalReceipts
    ) : Collection
    {
        try {
            return WithdrawalReceiptProofDocument::query()
                ->whereIn('receipt_id', $withdrawalReceipts->pluck('id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/withdrawalReceiptProofDocument.' . __FUNCTION__),
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
            return WithdrawalReceiptProofDocument::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/withdrawalReceiptProofDocument.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}