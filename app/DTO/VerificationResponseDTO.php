<?php
namespace App\DTO;

/**
 * @OA\Schema(
 *     schema="VerificationResponseDTO",
 *     type="object",
 *     @OA\Property(
 *        property="data",
 *        type="object",
 *        @OA\Property(
 *          property="issuer",
 *          type="string",
 *          description="Issuer of the certificate"
 *        ),
 *         @OA\Property(
 *          property="result",
 *          type="string",
 *          description="Result of the verification"
 *         )
 *      )
 *  )
 **/
class VerificationResponseDTO
{
    public string $issuer;
    public string $result;

    public function __construct(string $issuer, string $result)
    {
        $this->issuer = $issuer;
        $this->result = $result;
    }
    public function toArray(): array
    {
        return [
            'data' => [
                'issuer' => $this->issuer,
                'result' => $this->result,
            ],
        ];
    }
}
