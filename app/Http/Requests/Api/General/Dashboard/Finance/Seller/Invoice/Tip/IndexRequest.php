<?php

namespace App\Http\Requests\Api\General\Dashboard\Finance\Seller\Invoice\Tip;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\General\Dashboard\Finance\Seller\Invoice\Tip
 */
class IndexRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'item_id'                   => 'integer|nullable',
            'invoice_id'                => 'integer|nullable',
            'username'                  => 'string|nullable',
            'date_from'                 => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'                   => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'amount'                    => 'integer|nullable',
            'vybe_types_ids'            => 'array|nullable',
            'vybe_types_ids.*'          => 'required|integer|between:1,3',
            'order_item_statuses_ids'   => 'array|nullable',
            'order_item_statuses_ids.*' => 'required|integer|between:1,10',
            'invoice_statuses_ids'      => 'array|nullable',
            'invoice_statuses_ids.*'    => 'required|integer|between:1,6',
            'page'                      => 'integer|nullable',
            'per_page'                  => 'integer|nullable',
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'item_id.integer'                    => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.item_id.integer'),
            'invoice_id.integer'                 => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.invoice_id.integer'),
            'username.string'                    => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.username.string'),
            'date_from.date_format'              => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.date_from.date_format'),
            'date_to.date_format'                => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.date_to.date_format'),
            'amount.integer'                     => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.amount.integer'),
            'vybe_types_ids.array'               => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.vybe_types_ids.array'),
            'vybe_types_ids.*.required'          => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.vybe_types_ids.*.required'),
            'vybe_types_ids.*.integer'           => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.vybe_types_ids.*.integer'),
            'vybe_types_ids.*.between'           => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.vybe_types_ids.*.between'),
            'order_item_statuses_ids.array'      => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.order_item_statuses_ids.array'),
            'order_item_statuses_ids.*.required' => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.order_item_statuses_ids.*.required'),
            'order_item_statuses_ids.*.integer'  => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.order_item_statuses_ids.*.integer'),
            'order_item_statuses_ids.*.between'  => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.order_item_statuses_ids.*.between'),
            'invoice_statuses_ids.array'         => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.invoice_statuses_ids.array'),
            'invoice_statuses_ids.*.required'    => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.invoice_statuses_ids.*.required'),
            'invoice_statuses_ids.*.integer'     => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.invoice_statuses_ids.*.integer'),
            'invoice_statuses_ids.*.between'     => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.invoice_statuses_ids.*.between'),
            'page.integer'                       => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.page.integer'),
            'per_page.integer'                   => trans('validations/api/general/dashboard/finance/seller/invoice/tip/index.per_page.integer')
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation() : void
    {
        $params = $this->all();

        $this->merge([
            'item_id'                 => isset($params['item_id']) ? (int) $params['item_id'] : null,
            'invoice_id'              => isset($params['invoice_id']) ? (int) $params['invoice_id'] : null,
            'amount'                  => isset($params['amount']) ? (int) $params['amount'] : null,
            'vybe_types_ids'          => isset($params['vybe_types_ids']) ? explodeUrlIds($params['vybe_types_ids']) : null,
            'order_item_statuses_ids' => isset($params['order_item_statuses_ids']) ? explodeUrlIds($params['order_item_statuses_ids']) : null,
            'invoice_statuses_ids'    => isset($params['invoice_statuses_ids']) ? explodeUrlIds($params['invoice_statuses_ids']) : null,
            'page'                    => isset($params['page']) ? (int) $params['page'] : null,
            'per_page'                => isset($params['per_page']) ? (int) $params['per_page'] : null
        ]);
    }

    /**
     * @param null $keys
     *
     * @return array
     */
    public function all($keys = null) : array
    {
        return array_merge(
            parent::all(),
            $this->route()->parameters()
        );
    }
}
