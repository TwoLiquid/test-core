<?php

namespace App\Http\Controllers\Api\General\Setting;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Setting\Interfaces\NotificationSettingControllerInterface;
use App\Http\Requests\Api\General\Setting\Notification\SaveChangesRequest;
use App\Repositories\Notification\NotificationSettingRepository;
use App\Services\Auth\AuthService;
use App\Services\Notification\NotificationSettingService;
use Illuminate\Http\JsonResponse;

/**
 * Class NotificationSettingController
 *
 * @package App\Http\Controllers\Api\General\Setting
 */
class NotificationSettingController extends BaseController implements NotificationSettingControllerInterface
{
    /**
     * @var NotificationSettingRepository
     */
    protected NotificationSettingRepository $notificationSettingRepository;

    /**
     * @var NotificationSettingService
     */
    protected NotificationSettingService $notificationSettingService;

    /**
     * NotificationSettingController constructor
     */
    public function __construct()
    {
        /** @var NotificationSettingRepository notificationSettingRepository */
        $this->notificationSettingRepository = new NotificationSettingRepository();

        /** @var NotificationSettingService notificationSettingService */
        $this->notificationSettingService = new NotificationSettingService();
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index() : JsonResponse
    {
        /**
         * Getting user notification settings
         */
        $notificationSettingsData = $this->notificationSettingService->getForUserSettings(
            AuthService::user()
        );

        return $this->respondWithSuccess(
            $notificationSettingsData,
            trans('validations/api/general/setting/notification/index.result.success')
        );
    }

    /**
     * @param SaveChangesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function saveChanges(
        SaveChangesRequest $request
    ) : JsonResponse
    {
        /**
         * Checking notification setting exists for user
         */
        if (!$this->notificationSettingRepository->existsForUser(
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/setting/notification/saveChanges.result.success.exists')
            );
        }

        /**
         * Updating notification setting
         */
        $this->notificationSettingRepository->update(
            AuthService::user()->notificationSettings,
            AuthService::user(),
            $request->input('notification_enable'),
            $request->input('email_followers_follows_you'),
            $request->input('email_followers_new_vybe'),
            $request->input('email_followers_new_event'),
            $request->input('messages_unread'),
            $request->input('email_orders_new'),
            $request->input('email_order_starts'),
            $request->input('email_order_pending'),
            $request->input('reschedule_info'),
            $request->input('review_new'),
            $request->input('review_waiting'),
            $request->input('withdrawals_info'),
            $request->input('email_invitation_info'),
            $request->input('tickets_new_order'),
            $request->input('miscellaneous_regarding'),
            $request->input('platform_followers_follows'),
            $request->input('platform_followers_new_vybe'),
            $request->input('platform_followers_new_event'),
            $request->input('platform_order_starts'),
            $request->input('platform_invitation_info'),
            $request->input('news_receive')
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/notification/saveChanges.result.success')
        );
    }
}
