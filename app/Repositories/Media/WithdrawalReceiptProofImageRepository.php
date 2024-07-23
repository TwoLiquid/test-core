<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Media\WithdrawalReceiptProofImage;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\WithdrawalReceiptProofImageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class WithdrawalReceiptProofImageRepository
 *
 * @package App\Repositories\Media
 */
class WithdrawalReceiptProofImageRepository extends BaseRepository implements WithdrawalReceiptProofImageRepositoryInterface
{
    /**
     * WithdrawalReceiptProofImageRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.withdrawalReceiptProofImage.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return WithdrawalReceiptProofImage|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?WithdrawalReceiptProofImage
    {
        try {
            return WithdrawalReceiptProofImage::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/withdrawalReceiptProofImage.' . __FUNCTION__),
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
            return WithdrawalReceiptProofImage::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/withdrawalReceiptProofImage.' . __FUNCTION__),
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
            return WithdrawalReceiptProofImage::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/withdrawalReceiptProofImage.' . __FUNCTION__),
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
            return WithdrawalReceiptProofImage::query()
                ->where('receipt_id', '=', $withdrawalReceipt->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/withdrawalReceiptProofImage.' . __FUNCTION__),
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
            return WithdrawalReceiptProofImage::query()
                ->whereIn('receipt_id', $withdrawalReceipts->pluck('id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/withdrawalReceiptProofImage.' . __FUNCTION__),
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
            return WithdrawalReceiptProofImage::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/withdrawalReceiptProofImage.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}