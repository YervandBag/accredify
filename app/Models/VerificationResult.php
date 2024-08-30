<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// TODO: register rest properties (col)

/**
 * @OA\Schema(
 *     schema="VerificationResult",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="file_type",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="result",
 *         type="string"
 *     )
 * )
 */
class VerificationResult extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'result' => 'json'
        ];
    }
}
