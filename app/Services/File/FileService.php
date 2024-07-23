<?php

namespace App\Services\File;

use App\Services\File\Interfaces\FileServiceInterface;

/**
 * Class FileService
 *
 * @package App\Services\File
 */
class FileService implements FileServiceInterface
{
    /**
     * @param array $files
     *
     * @return array
     */
    public function sortFiles(
        array $files
    ) : array
    {
        $sortedFiles = [];

        /** @var array $file */
        foreach ($files as $file) {
            if (in_array($file['mime'], config('media.default.audio.allowedMimes'))) {
                $sortedFiles['audios'][] = $file;
            } elseif (in_array($file['mime'], config('media.default.video.allowedMimes'))) {
                $sortedFiles['videos'][] = $file;
            } elseif (in_array($file['mime'], config('media.default.image.allowedMimes'))) {
                $sortedFiles['images'][] = $file;
            } else {
                if (config('media.default.document.allowAllMimes') === false) {
                    if (in_array($file['mime'], config('media.default.document.allowedMimes'))) {
                        $sortedFiles['documents'][] = $file;
                    }
                } else {
                    $sortedFiles['documents'][] = $file;
                }
            }
        }

        return $sortedFiles;
    }

    /**
     * @param string $base64string
     *
     * @return float
     */
    function getSizeFromBase64String(
        string $base64string
    ) : float
    {
        $lengthInCharacters = strlen($base64string);
        $paddingCharacters = substr($base64string, -4);
        $numberOfPaddingCharacters = substr_count($paddingCharacters, '=');

        return (3 * ($lengthInCharacters / 4)) - $numberOfPaddingCharacters;
    }
}