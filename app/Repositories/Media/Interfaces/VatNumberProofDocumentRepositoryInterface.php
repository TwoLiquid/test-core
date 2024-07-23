<?php

namespace App\Repositories\Media\Interfaces;

use App\Models\MongoDb\User\Billing\VatNumberProof;
use App\Models\MySql\Media\VatNumberProofDocument;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VatNumberProofDocumentRepositoryInterface
 *
 * @package App\Repositories\Media\Interfaces
 */
interface VatNumberProofDocumentRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return VatNumberProofDocument|null
     */
    public function findById(
        ?int $id
    ) : ?VatNumberProofDocument;

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
     * @param VatNumberProof $vatNumberProof
     *
     * @return Collection
     */
    public function getByVatNumberProof(
        VatNumberProof $vatNumberProof
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param Collection $vatNumberProofs
     *
     * @return Collection
     */
    public function getByVatNumberProofs(
        Collection $vatNumberProofs
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
