<?php

namespace App\Transformers\Api\General\Setting\IdVerification;

use App\Models\MySql\Media\UserIdVerificationImage;
use App\Transformers\BaseTransformer;

/**
 * Class UserIdVerificationImageTransformer
 *
 * @package App\Transformers\Api\General\Setting\IdVerification
 */
class UserIdVerificationImageTransformer extends BaseTransformer
{
    /**
     * @param UserIdVerificationImage $userIdVerificationImage
     *
     * @return array
     */
    public function transform(UserIdVerificationImage $userIdVerificationImage) : array
    {
        return [
            'id'      => $userIdVerificationImage->id,
            'url'     => $userIdVerificationImage->url,
            'url_min' => $userIdVerificationImage->url_min,
            'mime'    => $userIdVerificationImage->mime
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_id_verification_image';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_id_verification_images';
    }
}
