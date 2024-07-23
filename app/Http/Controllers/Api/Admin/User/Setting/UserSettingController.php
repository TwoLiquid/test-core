<?php

namespace App\Http\Controllers\Api\Admin\User\Setting;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\User\Setting\Interfaces\UserSettingControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Setting\IndexRequest;
use App\Http\Requests\Api\Admin\User\Setting\UpdateRequest;
use App\Repositories\User\UserRepository;
use App\Settings\Aggregator\UserSettingAggregator;
use App\Transformers\Api\Admin\User\Setting\UserSettingPageTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class UserSettingController
 *
 * @package App\Http\Controllers\Api\Admin\User\Setting
 */
final class UserSettingController extends BaseController implements UserSettingControllerInterface
{
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var UserSettingAggregator
     */
    protected UserSettingAggregator $userSettingAggregator;

    /**
     * UserSettingController constructor
     */
    public function __construct()
    {
        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var UserSettingAggregator userSettingAggregator */
        $this->userSettingAggregator = new UserSettingAggregator();
    }

    /**
     * @param int $id
     * @param IndexRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        int $id,
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/setting/index.result.error.find.user')
            );
        }

        /**
         * Setting user to setting aggregator
         */
        $this->userSettingAggregator->setUser(
            $user
        );

        if ($request->input('category') == 'buyer') {
            return $this->respondWithSuccess(
                $this->transformItem([],
                    new UserSettingPageTransformer(
                        $this->userSettingAggregator->getBuyer(true)
                    )
                )['user_setting_page'],
                trans('validations/api/admin/user/setting/index.result.success')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new UserSettingPageTransformer(
                    $this->userSettingAggregator->getSeller(true)
                )
            )['user_setting_page'],
            trans('validations/api/admin/user/setting/index.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/setting/index.result.error.find.user')
            );
        }

        /**
         * Setting user to setting aggregator
         */
        $this->userSettingAggregator->setUser(
            $user
        );

        /**
         * Checking category
         */
        if ($request->input('category') == 'buyer') {

            /**
             * Updating buyer settings
             */
            $this->userSettingAggregator->saveBuyer(
                $request->input('settings')
            );
        } else {

            /**
             * Updating seller settings
             */
            $this->userSettingAggregator->saveSeller(
                $request->input('settings')
            );
        }

        return $this->respondWithSuccess([],
            trans('validations/api/admin/user/setting/update.result.success')
        );
    }
}
