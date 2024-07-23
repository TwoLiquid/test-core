<?php

namespace App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt;

use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt\Pdf\CompanyCredentialsTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class WithdrawalReceiptTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Withdrawal\Receipt
 */
class WithdrawalReceiptTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $withdrawalReceiptProofImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $withdrawalReceiptProofDocuments;

    /**
     * WithdrawalReceiptTransformer constructor
     *
     * @param EloquentCollection|null $withdrawalReceiptProofImages
     * @param EloquentCollection|null $withdrawalReceiptProofDocuments
     */
    public function __construct(
        EloquentCollection $withdrawalReceiptProofImages = null,
        EloquentCollection $withdrawalReceiptProofDocuments = null
    )
    {
        /** @var EloquentCollection withdrawalReceiptProofImages */
        $this->withdrawalReceiptProofImages = $withdrawalReceiptProofImages;

        /** @var EloquentCollection withdrawalReceiptProofDocuments */
        $this->withdrawalReceiptProofDocuments = $withdrawalReceiptProofDocuments;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'images',
        'documents',
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
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return Collection|null
     */
    public function includeImages(WithdrawalReceipt $withdrawalReceipt) : ?Collection
    {
        $withdrawalReceiptProofImages = $this->withdrawalReceiptProofImages?->filter(function ($item) use ($withdrawalReceipt) {
            return $item->receipt_id == $withdrawalReceipt->id;
        });

        return $withdrawalReceiptProofImages ? $this->collection($withdrawalReceiptProofImages, new WithdrawalReceiptProofImageTransformer) : null;
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     *
     * @return Collection|null
     */
    public function includeDocuments(WithdrawalReceipt $withdrawalReceipt) : ?Collection
    {
        $withdrawalReceiptProofDocuments = $this->withdrawalReceiptProofDocuments?->filter(function ($item) use ($withdrawalReceipt) {
            return $item->receipt_id == $withdrawalReceipt->id;
        });

        return $withdrawalReceiptProofDocuments ? $this->collection($withdrawalReceiptProofDocuments, new WithdrawalReceiptProofDocumentTransformer) : null;
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

        return $user ? $this->item($user, new UserTransformer) : null;
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
