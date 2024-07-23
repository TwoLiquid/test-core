<?php

namespace App\Microservices\Log\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserWalletTransactionLogCollectionResponse
 *
 * @property Collection $logs
 * @property array $pagination
 *
 * @package App\Microservices\Log\Responses
 */
class UserWalletTransactionLogCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $logs;

    /**
     * @var array
     */
    public array $pagination;

    /**
     * UserWalletTransactionLogCollectionResponse constructor
     *
     * @param array $logs
     * @param array $pagination
     * @param string|null $message
     */
    public function __construct(
        array $logs,
        array $pagination,
        ?string $message
    )
    {
        $this->logs = new Collection();
        $this->pagination = $pagination;

        /** @var object $log */
        foreach ($logs as $log) {
            $this->logs->push(
                new UserWalletTransactionLogResponse(
                    $log,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}