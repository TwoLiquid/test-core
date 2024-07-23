<?php

namespace App\Transformers\Api\Admin\Request\Finance\WithdrawalRequest;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageList;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Lists\Withdrawal\Receipt\Status\WithdrawalReceiptStatusList;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class FormTransformer
 *
 * @package App\Transformers\Api\Admin\Request\Finance\WithdrawalRequest
 */
class FormTransformer extends BaseTransformer
{
    /**
     * @var PaymentMethodRepository
     */
    protected PaymentMethodRepository $paymentMethodRepository;

    /**
     * FormTransformer constructor
     */
    public function __construct()
    {
        /** @var PaymentMethodRepository paymentMethodRepository */
        $this->paymentMethodRepository = new PaymentMethodRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'payout_methods',
        'user_balance_types',
        'languages',
        'payout_statuses',
        'request_statuses'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includePayoutMethods() : ?Collection
    {
        $payoutMethods = $this->paymentMethodRepository->getAllWithdrawalIntegrated();

        return $this->collection($payoutMethods, new PaymentMethodTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeUserBalanceTypes() : ?Collection
    {
        $userBalanceTypes = UserBalanceTypeList::getItems();

        return $this->collection($userBalanceTypes, new UserBalanceTypeTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeLanguages() : ?Collection
    {
        $languages = LanguageList::getTranslatableItems();

        return $this->collection($languages, new LanguageTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includePayoutStatuses() : ?Collection
    {
        $payoutStatuses = WithdrawalReceiptStatusList::getItems();

        return $this->collection($payoutStatuses, new WithdrawalReceiptStatusTransformer);
    }


    /**
     * @return Collection|null
     */
    public function includeRequestStatuses() : ?Collection
    {
        $requestStatuses = RequestStatusList::getItems();

        return $this->collection($requestStatuses, new RequestStatusTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'form';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'forms';
    }
}
