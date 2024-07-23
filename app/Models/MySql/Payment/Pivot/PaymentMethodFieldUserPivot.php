<?php

namespace App\Models\MySql\Payment\Pivot;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Eloquent;

/**
 * App\Models\MySql\Payment\Pivot\PaymentMethodFieldUserPivot
 *
 * @method static Builder|PaymentMethodFieldUserPivot newModelQuery()
 * @method static Builder|PaymentMethodFieldUserPivot newQuery()
 * @method static Builder|PaymentMethodFieldUserPivot query()
 * @mixin Eloquent
 */
class PaymentMethodFieldUserPivot extends Pivot
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'json'
    ];
}
