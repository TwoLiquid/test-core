<?php

namespace App\Services\Tip\Interfaces;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface TipInvoiceServiceInterface
 *
 * @package App\Services\Tip\Interfaces
 */
interface TipInvoiceServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param Collection $tipInvoices
     *
     * @return Collection
     */
    public function getForAdminBuyerStatusesByIds(
        Collection $tipInvoices
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $tips
     *
     * @return Collection
     */
    public function getForAdminBuyerStatusesByTipsIds(
        Collection $tips
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $tipInvoices
     *
     * @return Collection
     */
    public function getForAdminSellerStatusesByIds(
        Collection $tipInvoices
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $tips
     *
     * @return Collection
     */
    public function getForAdminSellerStatusesByTipsIds(
        Collection $tips
    ) : Collection;
}