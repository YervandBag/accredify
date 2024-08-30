<?php

namespace App\Validators;

use App\Services\VerificationService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class JsonContentValidator
{
    /**
     * Validate the given JSON data
     *
     * @param array $data
     * @return string|null
     *
     * @throws ValidationException
     */
    public function validate(array $data): ?string
    {
        $validation = Validator::make($data, [
            'data.id' => 'required|string',
            'data.name' => 'required|string',
            'data.recipient.name' => 'required|string',
            'data.recipient.email' => 'required|string',
            'data.issuer.name' => 'required|string',
            'data.issuer.identityProof.type' => 'required|string',
            'data.issuer.identityProof.key' => 'required|string',
            'data.issuer.identityProof.location' => 'required|string',
            'data.issued' => 'required|date',
            'signature.type' => 'required|string',
            'signature.targetHash' => 'required|string'
        ]);

        if ($validation->fails()) {
            $errorMessage = VerificationService::INVALID;
            $messages = $validation->messages()->keys();

            foreach ($messages as $message) {
                if ($message === 'data.recipient.name' || $message === 'data.recipient.email') {
                    return VerificationService::INVALID_RECIPIENT;
                }

                if ($message === 'data.issuer.name' || strpos($message, 'data.issuer.identityProof') !== false) {
                    return VerificationService::INVALID_ISSUER;
                }
            }

            return $errorMessage;
        }

        return null;
    }
}
