<?php

use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Lists\Payment\Type\PaymentTypeListItem;

/**
 * This is a file with global helper functions
 */

if (!function_exists('generateRandomString')) {

    /**
     * @param bool $letters
     * @param bool $uppercase
     * @param bool $numerals
     * @param int $length
     *
     * @return string
     */
    function generateRandomString(
        bool $letters = true,
        bool $uppercase = true,
        bool $numerals = true,
        int $length = 20
    ) : string
    {
        $characters = '';

        if ($letters) {
            $characters = 'abcdefghijklmnopqrstuvwxyz';
        }

        if ($uppercase) {
            $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        if ($numerals) {
            $characters .= '0123456789';
        }

        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
}

if (!function_exists('compareStrings')) {

    /**
     * @param string|null $firstString
     * @param string|null $secondString
     *
     * @return bool
     */
    function compareStrings(
        ?string $firstString,
        ?string $secondString
    ) : bool
    {
        if (strcmp($firstString, $secondString) == 0) {
            return true;
        }

        return false;
    }
}

if (!function_exists('generateFileName')) {

    /**
     * @return string
     */
    function generateFileName() : string
    {
        return Str::uuid();
    }
}

if (!function_exists('generateFilePath')) {

    /**
     * @param string $folderName
     * @param string $extension
     *
     * @return string
     */
    function generateFilePath(
        string $folderName,
        string $extension
    ) : string
    {
        return implode('/', [
            'uploads',
            $folderName,
            generateFileName() . '.' . $extension
        ]);
    }
}

if (!function_exists('generateFullStorageLink')) {

    /**
     * @param string $fileUrl
     *
     * @return string
     */
    function generateFullStorageLink(
        string $fileUrl
    ) : string
    {
        return config('app.url') . 'storage/' . $fileUrl;
    }
}

if (!function_exists('generateCodeByName')) {

    /**
     * @param string $name
     *
     * @return string
     */
    function generateCodeByName(
        string $name
    ) : string
    {
        return strtolower(str_replace([
            ' ',
            '\''
        ], '-', $name));
    }
}

if (!function_exists('preparePhoneNumber')) {

    /**
     * @param string $phone
     *
     * @return string
     */
    function preparePhoneNumber(
        string $phone
    ) : string
    {
        return '+' . preg_replace("/[^0-9]/", '', trim($phone));
    }
}

if (!function_exists('getImageExtensionFromMimeType')) {

    /**
     * @param string $mime
     *
     * @return string|null
     */
    function getImageExtensionFromMimeType(
        string $mime
    ) : ?string
    {
        if ($mime == 'image/jpeg') {
            return 'jpg';
        } elseif ($mime == 'image/png') {
            return 'png';
        } elseif ($mime == 'image/gif') {
            return 'gif';
        }

        return null;
    }
}

if (!function_exists('prepareSyncParameters')) {

    /**
     * @param array $items
     * @param string $keyNeedle
     *
     * @return array
     */
    function prepareSyncParameters(
        array $items,
        string $keyNeedle
    ) : array
    {
        $convertedItems = [];

        foreach ($items as $itemArray) {
            $keyValue = $itemArray[$keyNeedle . '_id'];
            unset($itemArray[$keyNeedle . '_id']);

            $parameters = [];
            foreach ($itemArray as $itemArrayKey => $itemArrayParameter) {
                if (isTimestampIsoValid($itemArrayParameter)) {
                    $parameters[$itemArrayKey] = Carbon::parse($itemArrayParameter)
                        ->format('Y-m-d H:i:s');
                } elseif (is_string($itemArrayParameter)) {
                    $parameters[$itemArrayKey] = trim($itemArrayParameter);
                } else {
                    $parameters[$itemArrayKey] = $itemArrayParameter;
                }
            }

            $convertedItems[$keyValue] = $parameters;
        }

        return $convertedItems;
    }
}

if (!function_exists('prepareSearchString')) {

    /**
     * @param string|null $search
     *
     * @return string|null
     */
    function prepareSearchString(
        ?string $search
    ) : ?string
    {
        if (is_string($search)) {
            if ($search == '') {
                return null;
            }

            return trim($search);
        }

        return null;
    }
}

if (!function_exists('getFileNameFromUrl')) {

    /**
     * @param string $url
     *
     * @return string|null
     */
    function getFileNameFromUrl(
        string $url
    ) : ?string
    {
        /** @var array $urlPatterns */
        $urlPatterns = explode('/', $url);

        /** @var array $fileNamePatterns */
        $fileNamePatterns = explode('.', $urlPatterns[count($urlPatterns) - 1]);

        if (isset($fileNamePatterns[0])) {
            return $fileNamePatterns[0];
        }

        return $urlPatterns[count($urlPatterns) - 1];
    }
}

if (!function_exists('isTimestampIsoValid')) {

    /**
     * @param string|null $timestamp
     *
     * @return bool
     */
    function isTimestampIsoValid(
        ?string $timestamp
    ) : bool
    {
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}(?:\.\d*)?)((-(\d{2}):(\d{2})|Z)?)$/', $timestamp) > 0) {
            return true;
        }

        return false;
    }
}

if (!function_exists('getSizeFromBase64String')) {

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

if (!function_exists('getMinimizesFilePath')) {

    /**
     * @param string $filePath
     *
     * @return string
     */
    function getMinimizesFilePath(
        string $filePath
    ) : string
    {
        $filePathPatterns = explode('.', $filePath);

        return implode('.', [
            $filePathPatterns[0] . '_min',
            $filePathPatterns[1]
        ]);
    }
}

if (!function_exists('getRandomDateTime')) {

    /**
     * @return Carbon
     */
    function getRandomDateTime() : Carbon
    {
        return Carbon::now()->addDays(
            rand(2, 60)
        );
    }
}

if (!function_exists('generateCodeFromName')) {

    /**
     * @param string $name
     *
     * @return string
     */
    function generateCodeFromName(
        string $name
    ) : string
    {
        $code = strtolower(
            trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name))
        );

        if (str_ends_with($code, '-')) {
            $code = substr($code, 0, -1);
        }

        return trim(preg_replace('/--/', '-', $code));
    }
}

if (!function_exists('explodeUrlIds')) {

    /**
     * @param string $array
     *
     * @return array
     */
    function explodeUrlIds(
        string $array
    ) : array
    {
        $ids = [];

        foreach (explode(',', $array) as $id) {
            $ids[] = (int) $id;
        }

        return $ids;
    }
}

if (!function_exists('explodeUrlStrings')) {

    /**
     * @param string $array
     *
     * @return array
     */
    function explodeUrlStrings(
        string $array
    ) : array
    {
        $ids = [];

        foreach (explode(',', $array) as $id) {
            $ids[] = $id;
        }

        return $ids;
    }
}

if (!function_exists('isTextSingleEmoji')) {

    /**
     * @param string $text
     *
     * @return bool
     */
    function isTextSingleEmoji(
        string $text
    ) : bool
    {
        $str = preg_replace('/\s+/', '', $text);
        $emojis = [':bossy:',':calm:',':curious:',':friendly:',':funny:',':imaginative:',':likeme:',':mysterious:',':serious:',':shy:'];
        return in_array($str, $emojis);
    }
}

if (!function_exists('paginateCollection')) {

    /**
     * @param Collection $collection
     * @param int $perPage
     * @param string $pageName
     * @param null $fragment
     *
     * @return LengthAwarePaginator
     */
    function paginateCollection(
        Collection $collection,
        int $perPage,
        string $pageName = 'page',
        $fragment = null
    ) : LengthAwarePaginator
    {
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage($pageName);
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage);
        parse_str(request()->getQueryString(), $query);
        unset($query[$pageName]);
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'pageName' => $pageName,
                'path'     => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
                'query'    => $query,
                'fragment' => $fragment
            ]
        );
    }
}

if (!function_exists('getTokenValueFromPayPalUrl')) {

    /**
     * @param string $url
     *
     * @return string
     */
    function getTokenValueFromPayPalUrl(
        string $url
    ) : string
    {
        return preg_replace(
            "/^https?:\/\/.*\?.*token=([^&]+).*$/", "$1",
            $url
        );
    }
}

if (!function_exists('generateUsername')) {

    /**
     * @return string
     */
    function generateUsername() : string
    {
        return 'User' . md5(time() . generateRandomString(6));
    }
}

if (!function_exists('getStringFromAnyValue')) {

    /**
     * @param $value
     *
     * @return string
     */
    function getStringFromAnyValue($value) : string
    {
        if (is_array($value)) {
            return json_encode($value);
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return (string) $value;
    }
}

if (!function_exists('parseIpAddressesString')) {

    /**
     * @param string $ipAddressesString
     *
     * @return array
     */
    function parseIpAddressesString(
        string $ipAddressesString
    ) : array
    {
        return array_map(
            'trim',
            explode(',', $ipAddressesString)
        );
    }
}

if (!function_exists('getCarbonDateTime')) {

    /**
     * @param string|null $dateTime
     *
     * @return Carbon|null
     */
    function getCarbonDateTime(
        ?string $dateTime
    ) : ?Carbon
    {
        if (!is_null($dateTime)) {
            return Carbon::parse($dateTime);
        }

        return null;
    }
}

if (!function_exists('getRandomFutureDateBySchedule')) {

    /**
     * @return Carbon
     */
    function getRandomFutureDateBySchedule() : Carbon
    {
        return Carbon::now()->addDays(rand(1, 30));
    }
}

if (!function_exists('compareTwoArrays')) {

    /**
     * @param array|null $array1
     * @param array|null $array2
     *
     * @return bool
     */
    function compareTwoArrays(
        ?array $array1,
        ?array $array2
    ) : bool
    {
        if ($array1 === null) {
            $array1 = [];
        }

        if ($array2 === null) {
            $array2 = [];
        }

        if ((is_null($array1) && $array2) ||
            (is_null($array2) && $array1)
        ) {
            return false;
        }

        if (!empty(array_diff($array1, $array2)) ||
            !empty(array_diff($array2, $array1))
        ) {
            return false;
        }

        return true;
    }
}

if (!function_exists('collectWordsData')) {

    /**
     * @param string $languageCode
     * @param array $words
     *
     * @return array
     */
    function collectWordsData(
        string $languageCode,
        array $words
    ) : array
    {
        $offensiveWordsData = [];

        /** @var array $word */
        foreach ($words as $word) {
            $offensiveWordsData[] = [
                'language_code' => $languageCode,
                'word'          => $word
            ];
        }

        return $offensiveWordsData;
    }
}

if (!function_exists('getWaitingFromDates')) {

    /**
     * @param Carbon $fromDate
     * @param Carbon $toDate
     *
     * @return string
     */
    function getWaitingFromDates(
        Carbon $fromDate,
        Carbon $toDate
    ) : string
    {
        $hours = $toDate->diffInHours($fromDate);
        $minutes = $toDate->diff($fromDate)->format('%I');
        return $hours . ':' . $minutes;
    }
}

if (!function_exists('getIsAdminFromJWT')) {

    /**
     * @return bool|null
     */
    function getIsAdminFromJWT() : ?bool
    {
        $token = request()->bearerToken();

        if ($token) {
            $tokenParts = explode('.', $token);

            if (isset($tokenParts[1])) {
                return json_decode(
                    base64_decode($tokenParts[1])
                )->is_admin;
            }
        }

        return null;
    }
}

if (!function_exists('getAuthIdFromJWT')) {

    /**
     * @return int|null
     */
    function getAuthIdFromJWT() : ?int
    {
        $token = request()->bearerToken();

        if ($token) {
            $tokenParts = explode('.', $token);

            if (isset($tokenParts[1])) {
                return json_decode(
                    base64_decode($tokenParts[1])
                )->auth_id;
            }
        }

        return null;
    }
}

if (!function_exists('makeMediaUrl')) {

    /**
     * @param string $url
     *
     * @return string
     */
    function makeMediaUrl(
        string $url
    ) : string
    {
        return config('microservices.media.storage_url') ?
            config('microservices.media.storage_url') . $url :
            $url;
    }
}

if (!function_exists('makeMinimizedUrl')) {

    /**
     * @param string $url
     *
     * @return string
     */
    function makeMinimizedMediaUrl(
        string $url
    ) : string
    {
        return str_replace_last('.', '_min.', $url);
    }
}

if (!function_exists('str_replace_last')) {

    /**
     * @param $search
     * @param $replace
     * @param $string
     *
     * @return string
     */
    function str_replace_last(
        $search,
        $replace,
        $string
    ) : string
    {
        if (($pos = strrpos($string, $search)) !== false) {
            $search_length = strlen($search);
            $string = substr_replace(
                $string,
                $replace,
                $pos,
                $search_length
            );
        }

        return $string;
    }
}

if (!function_exists('buildUnitsForSync')) {

    /**
     * @param array $unitsItems
     *
     * @return array
     */
    function buildUnitsForSync(
        array $unitsItems
    ) : array
    {
        $response = [];

        /** @var array $unitItem */
        foreach ($unitsItems as $unitItem) {
            if (isset($unitItem['id']) &&
                isset($unitItem['position'])
            ) {
                $response[$unitItem['id']] = [
                    'position' => $unitItem['position']
                ];
            }
        }

        return $response;
    }
}

if (!function_exists('buildUnitsForSync')) {

    /**
     * @param array $unitsItems
     *
     * @return array
     */
    function buildUnitsForSync(
        array $unitsItems
    ) : array
    {
        $response = [];

        /** @var array $unitItem */
        foreach ($unitsItems as $unitItem) {
            if (isset($unitItem['id']) &&
                isset($unitItem['position'])
            ) {
                $response[$unitItem['id']] = [
                    'position' => $unitItem['position']
                ];
            }
        }

        return $response;
    }
}

if (!function_exists('buildUnitsForSyncWithVisible')) {

    /**
     * @param array $unitsIds
     * @param bool $visible
     *
     * @return array
     */
    function buildUnitsForSyncWithVisible(
        array $unitsIds,
        bool $visible
    ) : array
    {
        $response = [];

        /** @var int $unitId */
        foreach ($unitsIds as $unitId) {
            $response[$unitId] = [
                'visible' => $visible
            ];
        }

        return $response;
    }
}

if (!function_exists('generatePaymentHash')) {

    /**
     * @return string
     */
    function generatePaymentHash() : string
    {
        return md5(generateRandomString(16) . time());
    }
}

if (!function_exists('getPaymentHash')) {

    /**
     * @param PaymentTypeListItem $paymentTypeListItem
     * @param int $id
     * @param string $hash
     *
     * @return string
     */
    function getPaymentHash(
        PaymentTypeListItem $paymentTypeListItem,
        int $id,
        string $hash
    ) : string
    {
        return md5($paymentTypeListItem->code . '-' . $id . '-' . $hash);
    }
}

if (!function_exists('getSqlWeekDay')) {

    /**
     * @param Carbon $datetime
     *
     * @return int
     */
    function getSqlWeekDay(
        Carbon $datetime
    ) : int
    {
        if ($datetime->weekday() != 0) {
            return $datetime->weekday() - 1;
        }

        return 7;
    }
}

if (!function_exists('mergeCollections')) {

    /**
     * @param array $collections
     *
     * @return Collection
     */
    function mergeCollections(
        array $collections
    ) : Collection
    {
        $response = new Collection();

        foreach ($collections as $collection) {
            if (!is_null($collection)) {
                foreach ($collection as $value) {
                    $response->push(
                        $value
                    );
                }
            }
        }

        return $response;
    }
}

if (!function_exists('getNextYearDecember')) {

    /**
     * @return Carbon
     */
    function getNextYearDecember() : Carbon
    {
        return Carbon::create(
            Carbon::now()->year + 1,
            '12'
        );
    }
}

if (!function_exists('getNextYearJuly')) {

    /**
     * @return Carbon
     */
    function getNextYearJuly() : Carbon
    {
        return Carbon::create(
            Carbon::now()->year + 1,
            '7'
        );
    }
}

if (!function_exists('getNextYearJuly')) {

    /**
     * @param string $offset
     *
     * @return int
     */
    function getSecondsFromStringOffset(
        string $offset
    ) : int
    {
        $sign = substr(trim($offset), 0, 1);

        if (str_contains(trim($offset), ':')) {
            $patterns = explode(':', trim($offset));

            $seconds = array_sum([
                (int) $patterns[0] * 60 * 60,
                (int) $patterns[1] * 60
            ]);

            if ($sign == '+') {
                return $seconds;
            } elseif ($sign == '-') {
                return -$seconds;
            }

            return 0;
        } elseif ($offset == '0') {
            return 0;
        }

        return (int) $offset * 60 * 60;
    }
}
