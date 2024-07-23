<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Media\PaymentMethodImage;
use App\Models\MySql\Payment\PaymentMethod;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\PaymentMethodImageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class PaymentMethodImageRepository
 *
 * @package App\Repositories\Media
 */
class PaymentMethodImageRepository extends BaseRepository implements PaymentMethodImageRepositoryInterface
{
    /**
     * PaymentMethodImageRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.paymentMethodImage.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return PaymentMethodImage|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?PaymentMethodImage
    {
        try {
            return PaymentMethodImage::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/paymentMethodImage.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return PaymentMethodImage|null
     *
     * @throws DatabaseException
     */
    public function findByPaymentMethod(
        PaymentMethod $paymentMethod
    ) : ?PaymentMethodImage
    {
        try {
            return PaymentMethodImage::query()
                ->where('method_id', '=', $paymentMethod->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/paymentMethodImage.' . __FUNCTION__),
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
            return PaymentMethodImage::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/paymentMethodImage.' . __FUNCTION__),
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
            return PaymentMethodImage::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/paymentMethodImage.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $paymentMethods
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByPaymentMethods(
        Collection $paymentMethods
    ) : Collection
    {
        try {
            return PaymentMethodImage::query()
                ->whereIn('method_id', $paymentMethods->pluck('id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/paymentMethodImage.' . __FUNCTION__),
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
            return PaymentMethodImage::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/paymentMethodImage.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}