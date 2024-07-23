<?php

namespace App\Settings\Request;

use App\Exceptions\DatabaseException;
use App\Settings\Base\RequestSetting;
use App\Settings\Request\Interfaces\ReviewApprovalSettingInterface;

/**
 * Class ReviewApprovalSetting
 *
 * @package App\Settings\Request
 */
class ReviewApprovalSetting extends RequestSetting implements ReviewApprovalSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Review requests';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'review_requests';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $approvalToPublishVybeReviews = [
        'code'           => 'approval_to_publish_vybe_reviews',
        'name'           => 'Require admin approval to publish vybe reviews',
        'type'           => 'boolean',
        'icon'           => null,
        'original_value' => true
    ];

    /**
     * @var array
     */
    protected array $approvalToPublishBuyerReviews = [
        'code'           => 'approval_to_publish_buyer_reviews',
        'name'           => 'Require admin approval to publish buyer reviews',
        'type'           => 'boolean',
        'icon'           => null,
        'original_value' => false
    ];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return bool|null
     *
     * @throws DatabaseException
     */
    public function getApprovalToPublishVybeReviews() : ?bool
    {
        /**
         * Getting default request setting
         */
        $requestSetting = $this->requestSettingRepository->findByCode(
            $this->code,
            $this->approvalToPublishVybeReviews['code']
        );

        /**
         * Checking default request setting existence
         */
        if ($requestSetting) {
            return (bool) $requestSetting->value;
        }

        return null;
    }

    /**
     * @return bool|null
     *
     * @throws DatabaseException
     */
    public function getApprovalToPublishBuyerReviews() : ?bool
    {
        /**
         * Getting default request setting
         */
        $requestSetting = $this->requestSettingRepository->findByCode(
            $this->code,
            $this->approvalToPublishBuyerReviews['code']
        );

        /**
         * Checking default request setting existence
         */
        if ($requestSetting) {
            return (bool) $requestSetting->value;
        }

        return null;
    }

    //--------------------------------------------------------------------------
    // Setters

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setApprovalToPublishVybeReviews(
        bool $value
    ) : void
    {
        /**
         * Updating request setting
         */
        $this->requestSettingRepository->updateValueByCode(
            $this->code,
            $this->approvalToPublishVybeReviews['code'],
            $value
        );
    }

    /**
     * @param bool $value
     *
     * @throws DatabaseException
     */
    public function setApprovalToPublishBuyerReviews(
        bool $value
    ) : void
    {
        /**
         * Updating request setting
         */
        $this->requestSettingRepository->updateValueByCode(
            $this->code,
            $this->approvalToPublishBuyerReviews['code'],
            $value
        );
    }

    //--------------------------------------------------------------------------
    // Build

    /**
     * @return array
     *
     * @throws DatabaseException
     */
    public function build() : array
    {
        return [
            'code'     => $this->code,
            'name'     => $this->name,
            'children' => array_filter([
                $this->processField(
                    false,
                    $this->approvalToPublishVybeReviews,
                    $this->getApprovalToPublishVybeReviews()
                ),
                $this->processField(
                    false,
                    $this->approvalToPublishBuyerReviews,
                    $this->getApprovalToPublishBuyerReviews()
                )
            ])
        ];
    }

    //--------------------------------------------------------------------------
    // Seed

    /**
     * @throws DatabaseException
     */
    public function seed() : void
    {
        /**
         * Checking default request setting existence
         */
        if (!$this->requestSettingRepository->existsByCode(
            $this->code,
            $this->approvalToPublishVybeReviews['code']
        )) {

            /**
             * Creating default request setting
             */
            $this->requestSettingRepository->store(
                $this->code,
                $this->approvalToPublishVybeReviews['code'],
                $this->approvalToPublishVybeReviews['original_value']
            );
        }

        /**
         * Checking default request setting existence
         */
        if (!$this->requestSettingRepository->existsByCode(
            $this->code,
            $this->approvalToPublishBuyerReviews['code']
        )) {

            /**
             * Creating default request setting
             */
            $this->requestSettingRepository->store(
                $this->code,
                $this->approvalToPublishBuyerReviews['code'],
                $this->approvalToPublishBuyerReviews['original_value']
            );
        }
    }
}