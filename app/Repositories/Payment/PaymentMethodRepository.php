<?php

namespace App\Repositories\Payment;

use App\Exceptions\DatabaseException;
use App\Lists\Payment\Method\Payment\Status\PaymentStatusList;
use App\Lists\Payment\Method\Payment\Status\PaymentStatusListItem;
use App\Lists\Payment\Method\Withdrawal\Status\PaymentMethodWithdrawalStatusList;
use App\Lists\Payment\Method\Withdrawal\Status\PaymentMethodWithdrawalStatusListItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Payment\Interfaces\PaymentMethodRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class PaymentMethodRepository
 *
 * @package App\Repositories\Payment
 */
class PaymentMethodRepository extends BaseRepository implements PaymentMethodRepositoryInterface
{
    /**
     * PaymentMethodRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.paymentMethod.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return PaymentMethod|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?PaymentMethod
    {
        try {
            return PaymentMethod::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $id
     *
     * @return PaymentMethod|null
     *
     * @throws DatabaseException
     */
    public function findFullById(
        ?int $id
    ) : ?PaymentMethod
    {
        try {
            return PaymentMethod::query()
                ->with([
                    'requests',
                    'fields',
                    'countryPlaces',
                    'excludedCountryPlaces'
                ])
                ->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $name
     *
     * @return PaymentMethod|null
     *
     * @throws DatabaseException
     */
    public function findByName(
        string $name
    ) : ?PaymentMethod
    {
        try {
            return PaymentMethod::query()
                ->where('name', '=', trim($name))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param string $code
     *
     * @return PaymentMethod|null
     *
     * @throws DatabaseException
     */
    public function findByCode(
        string $code
    ) : ?PaymentMethod
    {
        try {
            return PaymentMethod::query()
                ->where('code', '=', trim($code))
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
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
            return PaymentMethod::query()
                ->with([
                    'fields',
                    'countryPlaces',
                    'excludedCountryPlaces'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
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
            return PaymentMethod::query()
                ->with([
                    'fields',
                    'countryPlaces',
                    'excludedCountryPlaces'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllForAdmin() : Collection
    {
        try {
            return PaymentMethod::query()
                ->with([
                    'fields',
                    'countryPlaces',
                    'excludedCountryPlaces'
                ])
                ->where('standard', '=', false)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
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
    public function getAllForAdminPaginated(
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return PaymentMethod::query()
                ->with([
                    'fields',
                    'countryPlaces',
                    'excludedCountryPlaces'
                ])
                ->where('standard', '=', false)
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param bool|null $standard
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllPaymentIntegrated(
        ?bool $standard = null
    ) : Collection
    {
        try {
            return PaymentMethod::query()
                ->with([
                    'fields',
                    'countryPlaces',
                    'excludedCountryPlaces'
                ])
                ->when(!is_null($standard), function ($query) use ($standard) {
                    $query->where('standard', '=', $standard);
                })
                ->where('payment_status_id', '=', PaymentStatusList::getActive()->id)
                ->where('integrated','=', true)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForUser(
        User $user
    ) : Collection
    {
        try {
            return $user->payoutMethods()
                ->with([
                    'fields',
                    'countryPlaces',
                    'excludedCountryPlaces'
                ])
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllWithdrawalIntegrated() : Collection
    {
        try {
            return PaymentMethod::query()
                ->with([
                    'fields',
                    'countryPlaces',
                    'excludedCountryPlaces'
                ])
                ->where('withdrawal_status_id', '=', PaymentMethodWithdrawalStatusList::getActive()->id)
                ->where('integrated','=', true)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param User $user
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function existsForUser(
        PaymentMethod $paymentMethod,
        User $user
    ) : bool
    {
        try {
            return $user->payoutMethods()
                ->where('id', '=', $paymentMethod->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentStatusListItem $paymentStatusListItem
     * @param PaymentMethodWithdrawalStatusListItem $paymentMethodWithdrawalStatusListItem
     * @param string $name
     * @param string $code
     * @param float $paymentFee
     * @param bool $orderForm
     * @param array $displayName
     * @param array $durationTitle
     * @param array $durationAmount
     * @param array $feeTitle
     * @param array $feeAmount
     * @param bool $integrated
     * @param bool $standard
     *
     * @return PaymentMethod|null
     *
     * @throws DatabaseException
     */
    public function store(
        PaymentStatusListItem $paymentStatusListItem,
        PaymentMethodWithdrawalStatusListItem $paymentMethodWithdrawalStatusListItem,
        string $name,
        string $code,
        float $paymentFee,
        bool $orderForm,
        array $displayName,
        array $durationTitle,
        array $durationAmount,
        array $feeTitle,
        array $feeAmount,
        bool $integrated = false,
        bool $standard = false
    ) : ?PaymentMethod
    {
        try {
            return PaymentMethod::query()->create([
                'payment_status_id'    => $paymentStatusListItem->id,
                'withdrawal_status_id' => $paymentMethodWithdrawalStatusListItem->id,
                'name'                 => trim($name),
                'code'                 => trim($code),
                'payment_fee'          => $paymentFee,
                'order_form'           => $orderForm,
                'display_name'         => $displayName,
                'duration_title'       => $durationTitle,
                'duration_amount'      => $durationAmount,
                'fee_title'            => $feeTitle,
                'fee_amount'           => $feeAmount,
                'integrated'           => $integrated,
                'standard'             => $standard
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param PaymentStatusListItem|null $paymentStatusListItem
     * @param PaymentMethodWithdrawalStatusListItem|null $paymentMethodWithdrawalStatusListItem
     * @param string|null $name
     * @param string|null $code
     * @param float|null $paymentFee
     * @param bool|null $orderForm
     * @param array|null $displayName
     * @param array|null $durationTitle
     * @param array|null $durationAmount
     * @param array|null $feeTitle
     * @param array|null $feeAmount
     * @param bool|null $integrated
     * @param bool|null $standard
     *
     * @return PaymentMethod
     *
     * @throws DatabaseException
     */
    public function update(
        PaymentMethod $paymentMethod,
        ?PaymentStatusListItem $paymentStatusListItem,
        ?PaymentMethodWithdrawalStatusListItem $paymentMethodWithdrawalStatusListItem,
        ?string $name,
        ?string $code,
        ?float $paymentFee,
        ?bool $orderForm,
        ?array $displayName,
        ?array $durationTitle,
        ?array $durationAmount,
        ?array $feeTitle,
        ?array $feeAmount,
        ?bool $integrated = null,
        ?bool $standard = null
    ) : PaymentMethod
    {
        try {
            $paymentMethod->update([
                'payment_status_id'    => $paymentStatusListItem ? $paymentStatusListItem->id : $paymentMethod->payment_status_id,
                'withdrawal_status_id' => $paymentMethodWithdrawalStatusListItem ? $paymentMethodWithdrawalStatusListItem->id : $paymentMethod->withdrawal_status_id,
                'name'                 => $name ? trim($name) : $paymentMethod->name,
                'code'                 => $code ? trim($code) : $paymentMethod->code,
                'payment_fee'          => $paymentFee ?: $paymentMethod->payment_fee,
                'order_form'           => $orderForm ?: $paymentMethod->order_form,
                'display_name'         => $displayName ?: $paymentMethod->display_name,
                'duration_title'       => $durationTitle ?: $paymentMethod->duration_title,
                'duration_amount'      => $durationAmount ?: $paymentMethod->duration_amount,
                'fee_title'            => $feeTitle ?: $paymentMethod->fee_title,
                'fee_amount'           => $feeAmount ?: $paymentMethod->fee_amount,
                'integrated'           => $integrated ?: $paymentMethod->integrated,
                'standard'             => $standard ?: $paymentMethod->standard
            ]);

            return $paymentMethod;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param bool $integrated
     *
     * @return PaymentMethod
     *
     * @throws DatabaseException
     */
    public function updateIntegrated(
        PaymentMethod $paymentMethod,
        bool $integrated
    ) : PaymentMethod
    {
        try {
            $paymentMethod->update([
                'integrated' => $integrated
            ]);

            return $paymentMethod;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param CountryPlace $countryPlace
     * @param bool $excluded
     * @param bool|null $detaching
     * @return void
     * @throws DatabaseException
     */
    public function attachCountryPlace(
        PaymentMethod $paymentMethod,
        CountryPlace $countryPlace,
        bool $excluded = false,
        ?bool $detaching = false
    ) : void
    {
        try {
            $paymentMethod->countryPlaces()->attach(
                $countryPlace->place_id, [
                    'excluded' => $excluded
                ]
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param array $countryPlacesIds
     * @param bool|null $detaching
     *
     * @throws DatabaseException
     */
    public function attachCountryPlaces(
        PaymentMethod $paymentMethod,
        array $countryPlacesIds,
        ?bool $detaching = false
    ) : void
    {
        try {
            $paymentMethod->countryPlaces()->sync(
                $countryPlacesIds,
                $detaching
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param CountryPlace $countryPlace
     *
     * @throws DatabaseException
     */
    public function detachCountryPlace(
        PaymentMethod $paymentMethod,
        CountryPlace $countryPlace
    ) : void
    {
        try {
            $paymentMethod->countryPlaces()->detach([
                $countryPlace->place_id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param array $countryPlacesIds
     *
     * @throws DatabaseException
     */
    public function detachCountryPlaces(
        PaymentMethod $paymentMethod,
        array $countryPlacesIds
    ) : void
    {
        try {
            $paymentMethod->countryPlaces()->detach(
                $countryPlacesIds
            );
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        PaymentMethod $paymentMethod
    ) : bool
    {
        try {
            return $paymentMethod->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/payment/paymentMethod.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
