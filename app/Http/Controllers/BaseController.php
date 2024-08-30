<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Accordify simplified API version",
 *     version="1.0.0",
 *     description="Accordify simplified version",
 *     @OA\Contact(
 *         email="support@example.com"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */
class BaseController extends Controller
{

}
