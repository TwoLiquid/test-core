<?php

namespace App\Repositories\Payout;

use App\Exceptions\DatabaseException;
use App\Models\MongoDb\Payout\PayoutMethodRequest;
use App\Models\MongoDb\Payout\PayoutMethodRequestField;
use App\Models\MySql\Payment\PaymentMethodField;
use App\Repositories\BaseRepository;
use App\Repositories\Payout\Interfaces\PayoutMethodRequestFieldRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class PayoutMethodRequestFieldRepository
 *
 * @package App\Repositories\Payout
 */
class PayoutMethodRequestFieldRepository extends BaseRepository implements PayoutMethodRequestFieldRepositoryInterface
{
    /**
     * PayoutMethodRequestFieldRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.payoutMethodRequestField.perPage');
    }

    /**
     * @param string|null $id
     *
     * @return PayoutMethodRequestField|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?string $id
    ) : ?PayoutMethodRequestField
    {
        try {
            return PayoutMethodRequestField::query()
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequestField.' . __FUNCTION__),
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
            return PayoutMethodRequestField::query()
                ->with([
                    'method',
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequestField.' . __FUNCTION__),
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
            return PayoutMethodRequestField::query()
                ->with([
                    'method',
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequestField.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForRequest(
        PayoutMethodRequest $payoutMethodRequest
    ) : Collection
    {
        try {
            return PayoutMethodRequestField::query()
                ->where('request_id', '=', $payoutMethodRequest->_id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequestField.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PayoutMethodRequest $payoutMethodRequest
     * @param PaymentMethodField $paymentMethodField
     * @param $value
     *
     * @return PayoutMethodRequestField|null
     *
     * @throws DatabaseException
     */
    public function store(
        PayoutMethodRequest $payoutMethodRequest,
        PaymentMethodField $paymentMethodField,
        $value
    ) : ?PayoutMethodRequestField
    {
        try {
            return PayoutMethodRequestField::query()->create([
                'request_id' => $payoutMethodRequest->_id,
                'field_id'   => $paymentMethodField->id,
                'value'      => $value
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequestField.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PayoutMethodRequestField $payoutMethodRequestField
     * @param PayoutMethodRequest $payoutMethodRequest
     * @param PaymentMethodField $paymentMethodField
     * @param $value
     *
     * @return PayoutMethodRequestField
     *
     * @throws DatabaseException
     */
    public function update(
        PayoutMethodRequestField $payoutMethodRequestField,
        PayoutMethodRequest $payoutMethodRequest,
        PaymentMethodField $paymentMethodField,
        $value
    ) : PayoutMethodRequestField
    {
        try {
            $payoutMethodRequestField->update([
                'request_id' => $payoutMethodRequest->_id,
                'field_id'   => $paymentMethodField->id,
                'value'      => $value ?: $payoutMethodRequestField->value
            ]);

            return $payoutMethodRequestField;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequestField.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PayoutMethodRequestField $payoutMethodRequestField
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        PayoutMethodRequestField $payoutMethodRequestField
    ) : bool
    {
        try {
            return $payoutMethodRequestField->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payout/payoutMethodRequestField.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
