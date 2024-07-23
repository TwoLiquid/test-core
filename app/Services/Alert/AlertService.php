<?php

namespace App\Services\Alert;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValidationException;
use App\Lists\Alert\Align\AlertAlignList;
use App\Lists\Alert\Animation\AlertAnimationList;
use App\Lists\Alert\Cover\AlertCoverList;
use App\Lists\Alert\Logo\Align\AlertLogoAlignList;
use App\Lists\Alert\Text\Font\AlertTextFontList;
use App\Lists\Alert\Text\Style\AlertTextStyleList;
use App\Lists\Alert\Type\AlertTypeList;
use App\Lists\Alert\Type\AlertTypeListItem;
use App\Models\MySql\Alert\AlertProfanityFilter;
use App\Models\MySql\User\User;
use App\Repositories\Alert\AlertProfanityFilterRepository;
use App\Repositories\Alert\AlertProfanityWordRepository;
use App\Repositories\Alert\AlertRepository;
use App\Services\Alert\Interfaces\AlertServiceInterface;
use App\Services\File\MediaService;

/**
 * Class AlertService
 *
 * @package App\Services\Alert
 */
class AlertService implements AlertServiceInterface
{
    /**
     * @var AlertProfanityFilterRepository
     */
    protected AlertProfanityFilterRepository $alertProfanityFilterRepository;

    /**
     * @var AlertProfanityWordRepository
     */
    protected AlertProfanityWordRepository $alertProfanityWordRepository;

    /**
     * @var AlertRepository
     */
    protected AlertRepository $alertRepository;

    /**
     * @var MediaService
     */
    protected MediaService $mediaService;

    /**
     * AlertService constructor
     */
    public function __construct()
    {
        /** @var AlertProfanityFilterRepository alertProfanityFilterRepository */
        $this->alertProfanityFilterRepository = new AlertProfanityFilterRepository();

        /** @var AlertProfanityWordRepository alertProfanityWordRepository */
        $this->alertProfanityWordRepository = new AlertProfanityWordRepository();

        /** @var AlertRepository alertRepository */
        $this->alertRepository = new AlertRepository();

        /** @var MediaService mediaService */
        $this->mediaService = new MediaService();
    }

    /**
     * @param User $user
     *
     * @throws DatabaseException
     */
    public function createDefaultsToUser(
        User $user
    ) : void
    {
        /** @var AlertTypeListItem $alertTypeListItem */
        foreach (AlertTypeList::getItems() as $alertTypeListItem) {

            /**
             * Creating alert
             */
            $alert = $this->alertRepository->store(
                $user,
                $alertTypeListItem,
                AlertAnimationList::getDefault($alertTypeListItem),
                AlertAlignList::getDefault($alertTypeListItem),
                AlertTextFontList::getDefault($alertTypeListItem),
                AlertTextStyleList::getDefault($alertTypeListItem),
                AlertLogoAlignList::getDefault($alertTypeListItem),
                AlertCoverList::getDefault($alertTypeListItem),
                config('alert.' . $alertTypeListItem->code . '.duration'),
                config('alert.' . $alertTypeListItem->code . '.text_font_color'),
                config('alert.' . $alertTypeListItem->code . '.text_font_size'),
                config('alert.' . $alertTypeListItem->code . '.logo_color'),
                config('alert.' . $alertTypeListItem->code . '.volume'),
                config('alert.' . $alertTypeListItem->code . '.username'),
                config('alert.' . $alertTypeListItem->code . '.amount'),
                config('alert.' . $alertTypeListItem->code . '.action'),
                config('alert.' . $alertTypeListItem->code . '.message'),
                null
            );

            /**
             * Checking an alert type
             */
            if ($alertTypeListItem->isTipping()) {

                /**
                 * Creating alert profanity filter
                 */
                $this->alertProfanityFilterRepository->store(
                    $alert,
                    config('alert.profanity.replace'),
                    config('alert.profanity.replace_with'),
                    config('alert.profanity.hide_messages'),
                    config('alert.profanity.bad_words')
                );
            }
        }
    }

    /**
     * @param AlertProfanityFilter $alertProfanityFilter
     * @param array $words
     *
     * @throws DatabaseException
     */
    public function updateProfanityWords(
        AlertProfanityFilter $alertProfanityFilter,
        array $words
    ) : void
    {
        /**
         * Deleting all alert profanity words
         */
        $this->alertProfanityWordRepository->deleteAllByFilter(
            $alertProfanityFilter
        );

        /** @var string $word */
        foreach ($words as $word) {

            /**
             * Creating alert profanity word
             */
            $this->alertProfanityWordRepository->store(
                $alertProfanityFilter,
                $word
            );
        }
    }

    /**
     * @param array $images
     *
     * @return bool
     *
     * @throws ValidationException
     */
    public function validateImages(
        array $images
    ) : bool
    {
        /**
         * @var int $key
         * @var array $image
         */
        foreach ($images as $key => $image) {

            try {

                /**
                 * Validating alert image
                 */
                $this->mediaService->validateAlertImage(
                    $image['content'],
                    $image['mime']
                );
            } catch (BaseException $exception) {
                throw new ValidationException(
                    $exception->getHumanReadableMessage(),
                    'images.' . $key
                );
            }
        }

        return true;
    }

    /**
     * @param array $sounds
     *
     * @return bool
     *
     * @throws ValidationException
     */
    public function validateSounds(
        array $sounds
    ) : bool
    {
        /**
         * @var int $key
         * @var array $sound
         */
        foreach ($sounds as $key => $sound) {

            try {

                /**
                 * Validating alert sound
                 */
                $this->mediaService->validateAlertSound(
                    $sound['content'],
                    $sound['mime']
                );
            } catch (BaseException $exception) {
                throw new ValidationException(
                    $exception->getHumanReadableMessage(),
                    'sounds.' . $key
                );
            }
        }

        return true;
    }
}
