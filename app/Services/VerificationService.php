<?php

namespace App\Services;
use App\DTO\CertificateDTO;
use Psr\Log\LoggerInterface;

class VerificationService
{
    const INVALID = 'invalid';
    const VERIFIED = 'verified';
    const UNVERIFIED = 'unverified';
    const INVALID_ISSUER = 'invalid_issuer';
    const INVALID_RECIPIENT = 'invalid_recipient';
    const INVALID_SIGNATURE = 'invalid_signature';

    public function __construct(protected DnsService $dnsService, protected LoggerInterface $logger)
    {
    }

    /**
     * Verify certificate
     *
     * @param CertificateDTO $certificateDTO
     * @param string $targetHash
     * @return string
     */
    public function verifyCertificate(CertificateDTO $certificateDTO, string $targetHash): string
    {
        $isIssuerValid = $this->validateIssuer($certificateDTO->identityProofLocation, $certificateDTO->identityProofKey);
        if (!$isIssuerValid) {
            return self::INVALID_ISSUER;
        }

        $isIssuerSignature = $this->validateSignature($certificateDTO, $targetHash);
        if (!$isIssuerSignature) {
            return self::INVALID_SIGNATURE;
        }

        return self::VERIFIED;
    }

    /**
     * Validate issuer wallet address
     *
     * @param string $location
     * @param string $key
     * @return boolean
     */
    private function validateIssuer(string $location, string $key): bool
    {
        return $this->dnsService->verifyDomain($location, $key);
    }

    /**
     * Validate certificate signature
     *
     * @param CertificateDTO $certificateDTO
     * @param string $signatureHash
     * @return boolean
     */
    private function validateSignature(CertificateDTO $certificateDTO, string $targetHash): bool
    {
        $hashes = [];
        $flatCertificate = $certificateDTO->toFlatArray();
        foreach ($flatCertificate as $key => $value) {
            $hashes[] = hash('sha256', json_encode([$key => $value]));
        }

        sort($hashes);

        $hashSum = hash('sha256', implode('', $hashes));

        if ($hashSum !== $targetHash) {
            $this->logger->info('VerificationService@validateSignature:failed', [
                'hashSum' => $hashSum
            ]);
        }

        return $hashSum === $targetHash;
    }
}