<?php

namespace App\Http\Controllers\Api\Guest\Invoice;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Invoice\Interfaces\InvoiceStatusControllerInterface;
use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Transformers\Api\Guest\Invoice\Status\InvoiceStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class InvoiceStatusController
 *
 * @package App\Http\Controllers\Api\Guest\Invoice
 */
final class InvoiceStatusController extends BaseController implements InvoiceStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting invoice statuses
         */
        $invoiceStatusListItems = InvoiceStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($invoiceStatusListItems, new InvoiceStatusTransformer),
            trans('validations/api/guest/invoice/status/index.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting invoice status
         */
        $invoiceStatusListItem = InvoiceStatusList::getItem($id);

        /**
         * Checking invoice status existence
         */
        if (!$invoiceStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/invoice/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($invoiceStatusListItem, new InvoiceStatusTransformer),
            trans('validations/api/guest/invoice/status/show.result.success')
        );
    }
}
