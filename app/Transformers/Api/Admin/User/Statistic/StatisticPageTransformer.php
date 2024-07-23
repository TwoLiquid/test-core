<?php

namespace App\Transformers\Api\Admin\User\Statistic;

use App\Transformers\Api\Admin\User\Statistic\Block\CanceledSalesTransformer;
use App\Transformers\Api\Admin\User\Statistic\Block\DeclinedSalesTransformer;
use App\Transformers\Api\Admin\User\Statistic\Block\ExpiredSalesTransformer;
use App\Transformers\Api\Admin\User\Statistic\Block\FinishedSalesTransformer;
use App\Transformers\Api\Admin\User\Statistic\Block\PartialRefundDisputeSalesTransformer;
use App\Transformers\Api\Admin\User\Statistic\Block\StatisticAverageTimeTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class StatisticPageTransformer
 *
 * @package App\Transformers\Api\Admin\User\Statistic
 */
class StatisticPageTransformer extends BaseTransformer
{
    /**
     * @var int
     */
    protected int $totalCount;

    /**
     * @var int
     */
    protected int $averageTimeSeconds;

    /**
     * @var int
     */
    protected int $declinedSalesCount;

    /**
     * @var float
     */
    protected float $declinedSalesPercentage;

    /**
     * @var int
     */
    protected int $canceledSalesCount;

    /**
     * @var float
     */
    protected float $canceledSalesPercentage;

    /**
     * @var int
     */
    protected int $expiredSalesCount;

    /**
     * @var float
     */
    protected float $expiredSalesPercentage;

    /**
     * @var int
     */
    protected int $disputedSalesCount;

    /**
     * @var float
     */
    protected float $disputedSalesPercentage;

    /**
     * @var int
     */
    protected int $canceledDisputeSalesCount;

    /**
     * @var float
     */
    protected float $canceledDisputeSalesPercentage;

    /**
     * @var int
     */
    protected int $finishedDisputeSalesCount;

    /**
     * @var float
     */
    protected float $finishedDisputeSalesPercentage;

    /**
     * @var int
     */
    protected int $partialRefundDisputeCount;

    /**
     * @var float
     */
    protected float $partialRefundDisputePercentage;

    /**
     * @var int
     */
    protected int $finishedSalesCount;

    /**
     * @var float
     */
    protected float $finishedSalesPercentage;

    /**
     * StatisticPageTransformer constructor
     *
     * @param int $totalCount
     * @param int $averageTimeSeconds
     * @param int $declinedSalesCount
     * @param float $declinedSalesPercentage
     * @param int $canceledSalesCount
     * @param float $canceledSalesPercentage
     * @param int $expiredSalesCount
     * @param float $expiredSalesPercentage
     * @param int $disputedSalesCount
     * @param float $disputedSalesPercentage
     * @param int $canceledDisputeSalesCount
     * @param float $canceledDisputeSalesPercentage
     * @param int $finishedDisputeSalesCount
     * @param float $finishedDisputeSalesPercentage
     * @param int $partialRefundDisputeCount
     * @param float $partialRefundDisputePercentage
     * @param int $finishedSalesCount
     * @param float $finishedSalesPercentage
     */
    public function __construct(
        int $totalCount,
        int $averageTimeSeconds,
        int $declinedSalesCount,
        float $declinedSalesPercentage,
        int $canceledSalesCount,
        float $canceledSalesPercentage,
        int $expiredSalesCount,
        float $expiredSalesPercentage,
        int $disputedSalesCount,
        float $disputedSalesPercentage,
        int $canceledDisputeSalesCount,
        float $canceledDisputeSalesPercentage,
        int $finishedDisputeSalesCount,
        float $finishedDisputeSalesPercentage,
        int $partialRefundDisputeCount,
        float $partialRefundDisputePercentage,
        int $finishedSalesCount,
        float $finishedSalesPercentage
    )
    {
        /** @var int totalCount */
        $this->totalCount = $totalCount;

        /** @var int averageTimeSeconds */
        $this->averageTimeSeconds = $averageTimeSeconds;

        /** @var int declinedSalesCount */
        $this->declinedSalesCount = $declinedSalesCount;

        /** @var float declinedSalesPercentage */
        $this->declinedSalesPercentage = $declinedSalesPercentage;

        /** @var int canceledSalesCount */
        $this->canceledSalesCount = $canceledSalesCount;

        /** @var float canceledSalesPercentage */
        $this->canceledSalesPercentage = $canceledSalesPercentage;

        /** @var int expiredSalesCount */
        $this->expiredSalesCount = $expiredSalesCount;

        /** @var float expiredSalesPercentage */
        $this->expiredSalesPercentage = $expiredSalesPercentage;

        /** @var int disputedSalesCount */
        $this->disputedSalesCount = $disputedSalesCount;

        /** @var float disputedSalesPercentage */
        $this->disputedSalesPercentage = $disputedSalesPercentage;

        /** @var int canceledDisputeSalesCount */
        $this->canceledDisputeSalesCount = $canceledDisputeSalesCount;

        /** @var float canceledDisputeSalesPercentage */
        $this->canceledDisputeSalesPercentage = $canceledDisputeSalesPercentage;

        /** @var int finishedDisputeSalesCount */
        $this->finishedDisputeSalesCount = $finishedDisputeSalesCount;

        /** @var float finishedDisputeSalesPercentage */
        $this->finishedDisputeSalesPercentage = $finishedDisputeSalesPercentage;

        /** @var int partialRefundDisputeCount */
        $this->partialRefundDisputeCount = $partialRefundDisputeCount;

        /** @var float partialRefundDisputePercentage */
        $this->partialRefundDisputePercentage = $partialRefundDisputePercentage;

        /** @var int finishedSalesCount */
        $this->finishedSalesCount = $finishedSalesCount;

        /** @var float finishedSalesPercentage */
        $this->finishedSalesPercentage = $finishedSalesPercentage;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'average_time',
        'declined_sales',
        'canceled_sales',
        'expired_sales',
        'disputed_sales',
        'canceled_dispute_sales',
        'finished_dispute_sales',
        'partial_refund_dispute_sales',
        'finished_sales'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item|null
     */
    public function includeAverageTime() : ?Item
    {
        return $this->item([
            'seconds' => $this->averageTimeSeconds
        ], new StatisticAverageTimeTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeDeclinedSales() : ?Item
    {
        return $this->item([
            'amount'     => $this->declinedSalesCount,
            'total'      => $this->totalCount,
            'percentage' => $this->declinedSalesPercentage
        ], new DeclinedSalesTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeCanceledSales() : ?Item
    {
        return $this->item([
            'amount'     => $this->canceledSalesCount,
            'total'      => $this->totalCount,
            'percentage' => $this->canceledSalesPercentage
        ], new CanceledSalesTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeExpiredSales() : ?Item
    {
        return $this->item([
            'amount'     => $this->expiredSalesCount,
            'total'      => $this->totalCount,
            'percentage' => $this->expiredSalesPercentage
        ], new ExpiredSalesTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeDisputedSales() : ?Item
    {
        return $this->item([
            'amount'     => $this->disputedSalesCount,
            'total'      => $this->totalCount,
            'percentage' => $this->disputedSalesPercentage
        ], new ExpiredSalesTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeCanceledDisputeSales() : ?Item
    {
        return $this->item([
            'amount'     => $this->canceledDisputeSalesCount,
            'total'      => $this->totalCount,
            'percentage' => $this->canceledDisputeSalesPercentage
        ], new ExpiredSalesTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeFinishedDisputeSales() : ?Item
    {
        return $this->item([
            'amount'     => $this->finishedDisputeSalesCount,
            'total'      => $this->totalCount,
            'percentage' => $this->finishedDisputeSalesPercentage
        ], new ExpiredSalesTransformer);
    }

    /**
     * @return Item|null
     */
    public function includePartialRefundDisputeSales() : ?Item
    {
        return $this->item([
            'amount'     => $this->partialRefundDisputeCount,
            'total'      => $this->totalCount,
            'percentage' => $this->partialRefundDisputePercentage
        ], new PartialRefundDisputeSalesTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeFinishedSales() : ?Item
    {
        return $this->item([
            'amount'     => $this->finishedSalesCount,
            'total'      => $this->totalCount,
            'percentage' => $this->finishedSalesPercentage
        ], new FinishedSalesTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'statistic_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'statistic_pages';
    }
}
