<?php

namespace App\Http\Controllers\Api\General\Alert;

use App\Exceptions\DatabaseException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Alert\Interfaces\AlertControllerInterface;
use App\Http\Requests\Api\General\Alert\UpdateRequest;
use App\Lists\Alert\Align\AlertAlignList;
use App\Lists\Alert\Animation\AlertAnimationList;
use App\Lists\Alert\Cover\AlertCoverList;
use App\Lists\Alert\Logo\Align\AlertLogoAlignList;
use App\Lists\Alert\Text\Font\AlertTextFontList;
use App\Lists\Alert\Text\Style\AlertTextStyleList;
use App\Lists\Alert\Type\AlertTypeList;
use App\Microservices\Media\MediaMicroservice;
use App\Repositories\Alert\AlertProfanityFilterRepository;
use App\Repositories\Alert\AlertRepository;
use App\Repositories\Media\AlertImageRepository;
use App\Repositories\Media\AlertSoundRepository;
use App\Services\Alert\AlertService;
use App\Services\Auth\AuthService;
use App\Transformers\Api\General\Alert\AlertTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class AlertController
 *
 * @package App\Http\Controllers\Api\General\Alert
 */
final class AlertController extends BaseController implements AlertControllerInterface
{
    /**
     * @var AlertProfanityFilterRepository
     */
    protected AlertProfanityFilterRepository $alertProfanityFilterRepository;

    /**
     * @var AlertRepository
     */
    protected AlertRepository $alertRepository;

    /**
     * @var AlertService
     */
    protected AlertService $alertService;

    /**
     * @var AlertImageRepository
     */
    protected AlertImageRepository $alertImageRepository;

    /**
     * @var AlertSoundRepository
     */
    protected AlertSoundRepository $alertSoundRepository;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * AlertController constructor
     */
    public function __construct()
    {
        /** @var AlertProfanityFilterRepository alertProfanityFilterRepository */
        $this->alertProfanityFilterRepository = new AlertProfanityFilterRepository();

        /** @var AlertRepository alertRepository */
        $this->alertRepository = new AlertRepository();

        /** @var AlertService alertService */
        $this->alertService = new AlertService();

        /** @var AlertImageRepository alertImageRepository */
        $this->alertImageRepository = new AlertImageRepository();

        /** @var AlertSoundRepository alertSoundRepository */
        $this->alertSoundRepository = new AlertSoundRepository();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index() : JsonResponse
    {
        /**
         * Getting an alerts
         */
        $alerts = $this->alertRepository->getAllForUser(
            AuthService::user()
        );

        return $this->respondWithSuccess(
            $this->transformCollection($alerts, new AlertTransformer(
                $this->alertImageRepository->getByAlerts(
                    $alerts
                ),
                $this->alertSoundRepository->getByAlerts(
                    $alerts
                )
            )), trans('validations/api/general/alert/index.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws ValidationException
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting an alert
         */
        $alert = $this->alertRepository->findById($id);

        /**
         * Checking an alert existence
         */
        if (!$alert) {
            return $this->respondWithError(
                trans('validations/api/general/alert/update.result.error.find')
            );
        }

        /**
         * Checking images existence
         */
        if ($request->input('images')) {

            $this->alertService->validateImages(
                $request->input('images')
            );
        }

        /**
         * Checking sounds existence
         */
        if ($request->input('sounds')) {

            $this->alertService->validateSounds(
                $request->input('sounds')
            );
        }

        /**
         * Getting an alert type list item
         */
        $alertTypeListItem = AlertTypeList::getItem(
            $request->input('alert_id')
        );

        /**
         * Getting an alert animation list item
         */
        $alertAnimationListItem = AlertAnimationList::getItem(
            $request->input('animation_id')
        );

        /**
         * Getting an alert align list item
         */
        $alertAlignListItem = AlertAlignList::getItem(
            $request->input('align_id')
        );

        /**
         * Getting an alert text font list item
         */
        $alertTextFontListItem = AlertTextFontList::getItem(
            $request->input('text_font_id')
        );

        /**
         * Getting an alert text style list item
         */
        $alertTextStyleListItem = AlertTextStyleList::getItem(
            $request->input('text_style_id')
        );

        /**
         * Getting an alert logo align list item
         */
        $alertLogoAlignListItem = AlertLogoAlignList::getItem(
            $request->input('logo_align_id')
        );

        /**
         * Getting an alert cover list item
         */
        $alertCoverListItem = AlertCoverList::getItem(
            $request->input('cover_id')
        );

        /**
         * Updating alert
         */
        $alert = $this->alertRepository->update(
            $alert,
            null,
            $alertTypeListItem,
            $alertAnimationListItem,
            $alertAlignListItem,
            $alertTextFontListItem,
            $alertTextStyleListItem,
            $alertLogoAlignListItem,
            $alertCoverListItem,
            $request->input('duration'),
            $request->input('text_font_color'),
            $request->input('text_font_size'),
            $request->input('logo_color'),
            $request->input('volume'),
            $request->input('username'),
            $request->input('amount'),
            $request->input('action'),
            $request->input('message'),
            $request->input('widget_url')
        );

        /**
         * Checking profanity filter existence
         */
        if ($request->input('profanity_filter') &&
            $alert->filter
        ) {

            /**
             * Updating alert profanity filter
             */
            $this->alertProfanityFilterRepository->update(
                $alert->filter,
                null,
                $request->input('profanity_filter.replace'),
                $request->input('profanity_filter.replace_with'),
                $request->input('profanity_filter.hide_messages'),
                $request->input('profanity_filter.bad_words')
            );

            /**
             * Updating profanity words
             */
            $this->alertService->updateProfanityWords(
                $alert->filter,
                $request->input('profanity_filter.words')
            );
        }

        /**
         * Checking images existence
         */
        if ($request->input('images')) {

            try {
                $this->mediaMicroservice->storeAlertImages(
                    $alert,
                    $request->input('images')
                );
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Checking sounds existence
         */
        if ($request->input('sounds')) {

            try {
                $this->mediaMicroservice->storeAlertSounds(
                    $alert,
                    $request->input('sounds')
                );
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Checking deleted images existence
         */
        if ($request->input('deleted_images_ids')) {

            try {
                $this->mediaMicroservice->deleteAlertImages(
                    $request->input('deleted_images_ids')
                );
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Checking deleted sounds existence
         */
        if ($request->input('deleted_sounds_ids')) {

            try {
                $this->mediaMicroservice->deleteAlertSounds(
                    $request->input('deleted_sounds_ids')
                );
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        return $this->respondWithSuccess(
            $this->transformItem($alert, new AlertTransformer(
                $this->alertImageRepository->getByAlerts(
                    new Collection([$alert])
                ),
                $this->alertSoundRepository->getByAlerts(
                    new Collection([$alert])
                )
            )), trans('validations/api/general/alert/update.result.success')
        );
    }
}
