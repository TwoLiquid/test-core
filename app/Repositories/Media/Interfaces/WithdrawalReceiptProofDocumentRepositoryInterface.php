<?php

namespace App\Repositories\Media\Interfaces;

use App\Models\MySql\Media\WithdrawalReceiptProofDocument;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface WithdrawalReceiptProofDocumentRepositoryInterface
 *
 * @package App\Repositories\Media\Interfaces
 */
interface WithdrawalReceiptProofDocumentRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return WithdrawalReceiptProofDocument|null
     */
    public function findById(
        ?int $id
    ) : ?WithdrawalReceiptProofDocument;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return Collection
     */
    public function getByReceipt(
        WithdrawalReceipt $withdrawalReceipt
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param Collection $withdrawalReceipts
     *
     * @return Collection
     */
    public function getByReceipts(
        Collection $withdrawalReceipts
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param array $ids
     *
     * @return Collection
     */
    public function getByIds(
        array $ids
    ) : Collection;
}
