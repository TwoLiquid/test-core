<?php

namespace App\Transformers\Api\General\Dashboard\Finance\Seller\Withdrawal\Pdf;

use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt\Pdf\CompanyCredentialsTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class WithdrawalReceiptPdfTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Finance\Seller\Withdrawal\Pdf
 */
class WithdrawalReceiptPdfTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'credentials',
        'user',
        'method',
        'request',
        'status',
        'transactions'
    ];

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return array
     */
    public function transform(WithdrawalReceipt $withdrawalReceipt) : array
    {
        return [
            'id'           => $withdrawalReceipt->id,
            'full_id'      => $withdrawalReceipt->full_id,
            'description'  => $withdrawalReceipt->description,
            'amount'       => $withdrawalReceipt->amount,
            'amount_total' => $withdrawalReceipt->amount_total,
            'payment_fee'  => $withdrawalReceipt->payment_fee,
            'created_at'   => $withdrawalReceipt->created_at ?
                $withdrawalReceipt->created_at->format('Y-m-d\TH:i:s.v\Z') :
                null
        ];
    }

    /**
     * @return Item
     */
    public function includeCredentials() : Item
    {
        return $this->item([], new CompanyCredentialsTransformer);
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return Item|null
     */
    public function includeUser(WithdrawalReceipt $withdrawalReceipt) : ?Item
    {
        $user = null;

        if ($withdrawalReceipt->relationLoaded('user')) {
            $user = $withdrawalReceipt->user;
        }

        return $user ? $this->item($user, new UserShortTransformer) : null;
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return Item|null
     */
    public function includeMethod(WithdrawalReceipt $withdrawalReceipt) : ?Item
    {
        $payoutMethod = null;

        if ($withdrawalReceipt->relationLoaded('method')) {
            $payoutMethod = $withdrawalReceipt->method;
        }

        return $payoutMethod ? $this->item($payoutMethod, new PaymentMethodTransformer) : null;
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return Item|null
     */
    public function includeRequest(WithdrawalReceipt $withdrawalReceipt) : ?Item
    {
        $withdrawalRequest = null;

        if ($withdrawalReceipt->relationLoaded('request')) {
            $withdrawalRequest = $withdrawalReceipt->request;
        }

        return $withdrawalRequest ? $this->item($withdrawalRequest, new WithdrawalRequestTransformer) : null;
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return Item|null
     */
    public function includeStatus(WithdrawalReceipt $withdrawalReceipt) : ?Item
    {
        $status = $withdrawalReceipt->getStatus();

        return $status ? $this->item($status, new WithdrawalReceiptStatusTransformer) : null;
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return Collection|null
     */
    public function includeTransactions(WithdrawalReceipt $withdrawalReceipt) : ?Collection
    {
        $transactions = null;

        if ($withdrawalReceipt->relationLoaded('transactions')) {
            $transactions = $withdrawalReceipt->transactions;
        }

        return $transactions ? $this->collection($transactions, new WithdrawalTransactionTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'withdrawal_receipt';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'withdrawal_receipts';
    }
}
