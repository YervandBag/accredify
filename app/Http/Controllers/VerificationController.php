<?php

namespace App\Http\Controllers;

use App\DTO\CertificateDTO;
use App\DTO\VerificationResponseDTO;
use App\Http\Requests\VerificationFileRequest;
use App\Models\VerificationResult;
use App\Services\VerificationService;
use App\Validators\JsonContentValidator;
use OpenApi\Annotations as OA;
use Log;

class VerificationController extends BaseController
{
    /**
     * @OA\Post(
     *     path="/api/verify",
     *     summary="Verify certificate file",
     *     tags={"Certificate"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="json_file",
     *                     type="string",
     *                     format="binary",
     *                     description="The JSON file to be verified"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/VerificationResponseDTO")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Bad request"
     *     )
     * )
     */
    public function verify(
        VerificationFileRequest $request,
        JsonContentValidator $jsonContentValidator,
        VerificationService $verificationService
    ) {
        $verificationStatus = VerificationService::INVALID;

        try {
            $jsonContent = file_get_contents($request->file('json_file')->getPathName());
            $jsonBody = json_decode($jsonContent, true);
            
            // Validate json schema, or throw error and handle with catch...
            $errorMessage = $jsonContentValidator->validate($jsonBody);
            
            $verificationResult = new VerificationResult();
            // Get userId from logged-in user ...
            $verificationResult->user_id = 1; 

            if ($errorMessage) {
                $response = new VerificationResponseDTO(config('app.name'), $errorMessage);
                $verificationResult->result = $response;
                $verificationResult->save();
                return response()->json($response->toArray());
            }

            $certificateDto = new CertificateDTO($jsonBody['data']);

            // Verify certificate
            $verificationStatus = $verificationService->verifyCertificate($certificateDto, $jsonBody['signature']['targetHash']);
        } catch (\Throwable $th) {
            Log::error('VerificationController@verify', ['error' => $th->getMessage()]);
        }

        $response = new VerificationResponseDTO(config('app.name'), $verificationStatus);

        $verificationResult->result = $response;
        $verificationResult->save();

        return response()->json($response->toArray());
    }

    /**
     * @OA\Get(
     *     path="/api/history",
     *     summary="Verification results",
     *     tags={"Certificate"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/VerificationResult")
     *     ),
     * )
     */
    public function history()
    {
        // filter by..., paginate....
        $verificationResults = VerificationResult::all();
        return response()->json($verificationResults);
    }
}
