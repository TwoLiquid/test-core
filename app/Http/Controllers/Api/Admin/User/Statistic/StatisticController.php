<?php

namespace App\Http\Controllers\Api\Admin\User\Statistic;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\User\Statistic\Interfaces\StatisticControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Repositories\Order\OrderItemRepository;
use App\Repositories\User\UserRepository;
use App\Services\Order\OrderItemService;
use App\Transformers\Api\Admin\User\Statistic\StatisticPageTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class StatisticController
 *
 * @package App\Http\Controllers\Api\Admin\User\Statistic
 */
final class StatisticController extends BaseController implements StatisticControllerInterface
{
    /**
     * @var OrderItemRepository
     */
    protected OrderItemRepository $orderItemRepository;

    /**
     * @var OrderItemService
     */
    protected OrderItemService $orderItemService;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * VybeController constructor
     */
    public function __construct()
    {
        /** @var OrderItemRepository orderItemRepository */
        $this->orderItemRepository = new OrderItemRepository();

        /** @var OrderItemService orderItemService */
        $this->orderItemService = new OrderItemService();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/statistic/index.result.error.find')
            );
        }

        /**
         * Getting order items
         */
        $orderItems = $this->orderItemRepository->getForSellerStatistic(
            $user
        );

        return $this->respondWithSuccess(
            $this->transformItem([], new StatisticPageTransformer(
                $this->orderItemService->getTotalCount(
                    $orderItems
                ),
                $this->orderItemService->getAcceptAverageSeconds(
                    $orderItems
                ),
                $this->orderItemService->getCanceledCount(
                    $orderItems
                ),
                $this->orderItemService->getCanceledPercentage(
                    $orderItems
                ),
                $this->orderItemService->getCanceledCount(
                    $orderItems
                ),
                $this->orderItemService->getCanceledPercentage(
                    $orderItems
                ),
                $this->orderItemService->getExpiredCount(
                    $orderItems
                ),
                $this->orderItemService->getExpiredPercentage(
                    $orderItems
                ),
                $this->orderItemService->getDisputedCount(
                    $orderItems
                ),
                $this->orderItemService->getDisputedPercentage(
                    $orderItems
                ),
                $this->orderItemService->getCanceledDisputeCount(
                    $orderItems
                ),
                $this->orderItemService->getCanceledDisputePercentage(
                    $orderItems
                ),
                $this->orderItemService->getFinishedDisputeCount(
                    $orderItems
                ),
                $this->orderItemService->getFinishedDisputePercentage(
                    $orderItems
                ),
                $this->orderItemService->getPartialRefundDisputeCount(
                    $orderItems
                ),
                $this->orderItemService->getPartialRefundDisputePercentage(
                    $orderItems
                ),
                $this->orderItemService->getFinishedCount(
                    $orderItems
                ),
                $this->orderItemService->getFinishedPercentage(
                    $orderItems
                )
            ))['statistic_page'],
            trans('validations/api/admin/user/statistic/index.result.success')
        );
    }
}
