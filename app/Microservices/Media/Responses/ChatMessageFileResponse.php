<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Support\Str;

/**
 * Class ChatMessageFileResponse
 *
 * @property array $headers
 * @property string $contents
 * @property string $fileName
 *
 * @package App\Microservices\Media\Responses
 */
class ChatMessageFileResponse
{
    /**
     * @var array
     */
    public array $headers;

    /**
     * @var string
     */
    public string $contents;

    /**
     * @var string
     */
    public string $fileName;

    /**
     * ChatMessageFileResponse constructor
     *
     * @param array $headers
     * @param string $contents
     */
    public function __construct(
        array $headers,
        string $contents
    )
    {
        $this->headers = $headers;
        $this->contents = $contents;
        $this->fileName = Str::uuid();
    }
}