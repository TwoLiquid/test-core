<?php

namespace App\Services\Tip;

use App\Exceptions\DatabaseException;
use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Lists\Invoice\Status\InvoiceStatusListItem;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Models\MySql\Tip\Tip;
use App\Models\MySql\Tip\TipInvoice;
use App\Repositories\Tip\TipInvoiceRepository;
use App\Services\Tip\Interfaces\TipInvoiceServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TipInvoiceService
 *
 * @package App\Services\Tip
 */
class TipInvoiceService implements TipInvoiceServiceInterface
{
    /**
     * @var TipInvoiceRepository
     */
    protected TipInvoiceRepository $tipInvoiceRepository;

    /**
     * TipInvoiceService constructor
     */
    public function __construct()
    {
        /** @var TipInvoiceRepository tipInvoiceRepository */
        $this->tipInvoiceRepository = new TipInvoiceRepository();
    }

    /**
     * @param Collection $tipInvoices
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminBuyerStatusesByIds(
        Collection $tipInvoices
    ) : Collection
    {
        $tipInvoicesIds = [];

        /** @var TipInvoice $tipInvoice */
        foreach ($tipInvoices as $tipInvoice) {
            if (!in_array($tipInvoice->id, $tipInvoicesIds)) {
                $tipInvoicesIds[] = $tipInvoice->id;
            }
        }

        /**
         * Getting tip invoices statuses count
         */
        $tipInvoiceStatusesCounts = $this->tipInvoiceRepository->getStatusesByIdsCount(
            InvoiceTypeList::getTipBuyer(),
            $tipInvoicesIds
        );

        /**
         * Getting tip invoices statuses
         */
        $tipBuyerInvoiceStatuses = InvoiceStatusList::getAllForTipBuyer();

        /** @var InvoiceStatusListItem $tipBuyerInvoiceStatus */
        foreach ($tipBuyerInvoiceStatuses as $tipBuyerInvoiceStatus) {

            /**
             * Setting tip invoice status count
             */
            $tipBuyerInvoiceStatus->setCount(
                $tipInvoiceStatusesCounts->getAttribute(
                    $tipBuyerInvoiceStatus->code
                )
            );
        }

        return $tipBuyerInvoiceStatuses;
    }

    /**
     * @param Collection $tips
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminBuyerStatusesByTipsIds(
        Collection $tips
    ) : Collection
    {
        $tipInvoicesIds = [];

        /** @var Tip $tip */
        foreach ($tips as $tip) {
            if (!in_array($tip->getBuyerInvoice()->id, $tipInvoicesIds)) {
                $tipInvoicesIds[] = $tip->getBuyerInvoice()->id;
            }
        }

        /**
         * Getting tip invoices statuses count
         */
        $tipInvoiceStatusesCounts = $this->tipInvoiceRepository->getStatusesByIdsCount(
            InvoiceTypeList::getTipBuyer(),
            $tipInvoicesIds
        );

        /**
         * Getting tip invoices statuses
         */
        $tipBuyerInvoiceStatuses = InvoiceStatusList::getAllForTipBuyer();

        /** @var InvoiceStatusListItem $tipBuyerInvoiceStatus */
        foreach ($tipBuyerInvoiceStatuses as $tipBuyerInvoiceStatus) {

            /**
             * Setting tip invoice status count
             */
            $tipBuyerInvoiceStatus->setCount(
                $tipInvoiceStatusesCounts->getAttribute(
                    $tipBuyerInvoiceStatus->code
                )
            );
        }

        return $tipBuyerInvoiceStatuses;
    }

    /**
     * @param Collection $tipInvoices
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminSellerStatusesByIds(
        Collection $tipInvoices
    ) : Collection
    {
        $tipInvoicesIds = [];

        /** @var TipInvoice $tipInvoice */
        foreach ($tipInvoices as $tipInvoice) {
            if (!in_array($tipInvoice->id, $tipInvoicesIds)) {
                $tipInvoicesIds[] = $tipInvoice->id;
            }
        }

        /**
         * Getting tip invoices statuses count
         */
        $tipInvoiceStatusesCounts = $this->tipInvoiceRepository->getStatusesByIdsCount(
            InvoiceTypeList::getTipSeller(),
            $tipInvoicesIds
        );

        /**
         * Getting tip invoices statuses
         */
        $tipSellerInvoiceStatuses = InvoiceStatusList::getAllForTipSeller();

        /** @var InvoiceStatusListItem $tipSellerInvoiceStatus */
        foreach ($tipSellerInvoiceStatuses as $tipSellerInvoiceStatus) {

            /**
             * Setting tip invoice status count
             */
            $tipSellerInvoiceStatus->setCount(
                $tipInvoiceStatusesCounts->getAttribute(
                    $tipSellerInvoiceStatus->code
                )
            );
        }

        return $tipSellerInvoiceStatuses;
    }

    /**
     * @param Collection $tips
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminSellerStatusesByTipsIds(
        Collection $tips
    ) : Collection
    {
        $tipInvoicesIds = [];

        /** @var Tip $tip */
        foreach ($tips as $tip) {
            if (!in_array($tip->getSellerInvoice()->id, $tipInvoicesIds)) {
                $tipInvoicesIds[] = $tip->getSellerInvoice()->id;
            }
        }

        /**
         * Getting tip invoices statuses count
         */
        $tipInvoiceStatusesCounts = $this->tipInvoiceRepository->getStatusesByIdsCount(
            InvoiceTypeList::getTipSeller(),
            $tipInvoicesIds
        );

        /**
         * Getting tip invoices statuses
         */
        $tipSellerInvoiceStatuses = InvoiceStatusList::getAllForTipSeller();

        /** @var InvoiceStatusListItem $tipSellerInvoiceStatus */
        foreach ($tipSellerInvoiceStatuses as $tipSellerInvoiceStatus) {

            /**
             * Setting tip invoice status count
             */
            $tipSellerInvoiceStatus->setCount(
                $tipInvoiceStatusesCounts->getAttribute(
                    $tipSellerInvoiceStatus->code
                )
            );
        }

        return $tipSellerInvoiceStatuses;
    }
}
