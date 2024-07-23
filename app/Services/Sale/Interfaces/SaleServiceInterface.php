<?php

namespace App\Services\Sale\Interfaces;

use App\Models\MySql\Order\Order;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface SaleServiceInterface
 *
 * @package App\Services\Sale\Interfaces
 */
interface SaleServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param Order $order
     *
     * @return Collection
     */
    public function createForOrder(
        Order $order
    ) : Collection;

    /**
     * This method provides creating data
     *
     * @param Collection $sales
     * @param string $code
     */
    public function addTransactionLogs(
        Collection $sales,
        string $code
    ) : void;
}