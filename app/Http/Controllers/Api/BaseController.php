<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transformers\TransformTrait;
use App\Support\Response\ApiResponseTrait;

/**
 * Class BaseController
 *
 * @package App\Http\Controllers\Api
 */
abstract class BaseController extends Controller
{
    use ApiResponseTrait;
    use TransformTrait;
}
