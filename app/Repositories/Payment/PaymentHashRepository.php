<?php

namespace App\Repositories\Payment;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Payment\PaymentHash;
use App\Repositories\BaseRepository;
use App\Repositories\Payment\Interfaces\PaymentHashRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class PaymentHashRepository
 *
 * @package App\Repositories\Payment
 */
class PaymentHashRepository extends BaseRepository implements PaymentHashRepositoryInterface
{
    /**
     * PaymentHashRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.paymentHash.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return PaymentHash|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?PaymentHash
    {
        try {
            return PaymentHash::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentHash.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $hash
     *
     * @return PaymentHash|null
     *
     * @throws DatabaseException
     */
    public function findByHash(
        string $hash
    ) : ?PaymentHash
    {
        try {
            return PaymentHash::query()
                ->where('hash', '=', trim($hash))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentHash.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $hash
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function existsForHash(
        string $hash
    ) : bool
    {
        try {
            return PaymentHash::query()
                ->where('hash', '=', trim($hash))
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentHash.' . __FUNCTION__),
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
            return PaymentHash::query()
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentHash.' . __FUNCTION__),
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
            return PaymentHash::query()
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentHash.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $externalId
     * @param string $hash
     *
     * @return PaymentHash|null
     *
     * @throws DatabaseException
     */
    public function store(
        string $externalId,
        string $hash
    ) : ?PaymentHash
    {
        try {
            return PaymentHash::query()->create([
                'external_id' => $externalId,
                'hash'        => $hash
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentHash.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentHash $paymentHash
     * @param string $externalId
     * @param string $hash
     *
     * @return PaymentHash
     *
     * @throws DatabaseException
     */
    public function update(
        PaymentHash $paymentHash,
        string $externalId,
        string $hash
    ) : PaymentHash
    {
        try {
            $paymentHash->update([
                'external_id' => $externalId ?: $paymentHash->external_id,
                'hash'        => $hash ?: $paymentHash->hash
            ]);

            return $paymentHash;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentHash.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentHash $paymentHash
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        PaymentHash $paymentHash
    ) : bool
    {
        try {
            return $paymentHash->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentHash.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
