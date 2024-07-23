<?php

namespace App\Repositories\Payment;

use App\Exceptions\DatabaseException;
use App\Lists\Payment\Method\Field\Type\PaymentMethodFieldTypeListItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Payment\PaymentMethodField;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Payment\Interfaces\PaymentMethodFieldRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class PaymentMethodFieldRepository
 *
 * @package App\Repositories\Payment
 */
class PaymentMethodFieldRepository extends BaseRepository implements PaymentMethodFieldRepositoryInterface
{
    /**
     * PaymentMethodFieldRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.paymentMethodField.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return PaymentMethodField|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?PaymentMethodField
    {
        try {
            return PaymentMethodField::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethodField.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllByMethod(
        PaymentMethod $paymentMethod
    ) : Collection
    {
        try {
            return PaymentMethodField::query()
                ->where('method_id', '=', $paymentMethod->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethodField.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param PaymentMethodFieldTypeListItem $paymentMethodFieldTypeListItem
     * @param array $title
     * @param array $placeholder
     * @param array|null $tooltip
     *
     * @return PaymentMethodField|null
     *
     * @throws DatabaseException
     */
    public function store(
        PaymentMethod $paymentMethod,
        PaymentMethodFieldTypeListItem $paymentMethodFieldTypeListItem,
        array $title,
        array $placeholder,
        ?array $tooltip
    ) : ?PaymentMethodField
    {
        try {
            return PaymentMethodField::query()->create([
                'method_id'   => $paymentMethod->id,
                'type_id'     => $paymentMethodFieldTypeListItem->id,
                'title'       => $title,
                'placeholder' => $placeholder,
                'tooltip'     => $tooltip
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethodField.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethodField $paymentMethodField
     * @param PaymentMethod|null $paymentMethod
     * @param PaymentMethodFieldTypeListItem|null $paymentMethodFieldTypeListItem
     * @param array|null $title
     * @param array|null $placeholder
     * @param array|null $tooltip
     *
     * @return PaymentMethodField
     *
     * @throws DatabaseException
     */
    public function update(
        PaymentMethodField $paymentMethodField,
        ?PaymentMethod $paymentMethod,
        ?PaymentMethodFieldTypeListItem $paymentMethodFieldTypeListItem,
        ?array $title,
        ?array $placeholder,
        ?array $tooltip
    ) : PaymentMethodField
    {
        try {
            $paymentMethodField->update([
                'method_id'   => $paymentMethod ? $paymentMethod->id : $paymentMethodField->method_id,
                'type_id'     => $paymentMethodFieldTypeListItem ? $paymentMethodFieldTypeListItem->id : $paymentMethodField->type_id,
                'title'       => $title ?: $paymentMethodField->title,
                'placeholder' => $placeholder ?: $paymentMethodField->placeholder,
                'tooltip'     => $tooltip ?: $paymentMethodField->tooltip
            ]);

            return $paymentMethodField;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethodField.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethodField $paymentMethodField
     * @param User $user
     * @param $value
     *
     * @throws DatabaseException
     */
    public function attachUser(
        PaymentMethodField $paymentMethodField,
        User $user,
        $value
    ) : void
    {
        try {
            $paymentMethodField->users()->attach(
                $user->id, [
                    'value' => $value
                ]
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethodField.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethodField $paymentMethodField
     * @param User $user
     *
     * @throws DatabaseException
     */
    public function detachUser(
        PaymentMethodField $paymentMethodField,
        User $user
    ) : void
    {
        try {
            $paymentMethodField->users()->detach([
                $user->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethodField.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethodField $paymentMethodField
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        PaymentMethodField $paymentMethodField
    ) : bool
    {
        try {
            return $paymentMethodField->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethodField.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}