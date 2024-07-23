<?php

namespace App\Transformers\Api\Admin\Navbar\Section;

use App\Transformers\BaseTransformer;

/**
 * Class InvoiceSectionTransformer
 *
 * @package App\Transformers\Api\Admin\Navbar\Section
 */
class InvoiceSectionTransformer extends BaseTransformer
{
    /**
     * @param array $invoices
     *
     * @return array
     */
    public function transform(array $invoices) : array
    {
        return $invoices;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'invoice';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'invoices';
    }
}
