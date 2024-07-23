<?php

namespace App\Http\Controllers\Api\Guest\Invoice;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Invoice\Interfaces\InvoiceTypeControllerInterface;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Transformers\Api\Guest\Invoice\Type\InvoiceTypeTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class InvoiceTypeController
 *
 * @package App\Http\Controllers\Api\Guest\Invoice
 */
final class InvoiceTypeController extends BaseController implements InvoiceTypeControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting an invoice types
         */
        $invoiceTypeListItems = InvoiceTypeList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($invoiceTypeListItems, new InvoiceTypeTransformer),
            trans('validations/api/guest/invoice/type/index.result.success')
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
         * Getting an invoice type
         */
        $invoiceTypeListItem = InvoiceTypeList::getItem($id);

        /**
         * Checking an invoice type existence
         */
        if (!$invoiceTypeListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/invoice/type/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($invoiceTypeListItem, new InvoiceTypeTransformer),
            trans('validations/api/guest/invoice/type/show.result.success')
        );
    }
}
