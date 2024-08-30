<?php

namespace Tests\Unit\Services;

use App\Services\VerificationService;
use App\Services\DnsService;
use App\DTO\CertificateDTO;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class VerificationServiceTest extends TestCase
{
    protected VerificationService $verificationService;
    protected $dnsService;

    protected function setUp(): void
    {
        // Error with facades (Log::...)
        $logger = $this->createMock(LoggerInterface::class);
        $this->dnsService = $this->createMock(DnsService::class);
        $this->verificationService = new VerificationService($this->dnsService, $logger);
    }

    public function testVerifyCertificateWithInvalidIssuer()
    {
        $certificateDTO = $this->getMockCertificateDTO();
        $targetHash = '288f94aadadf486cfdad84b9f4305f7d51eac62db18376d48180cc1dd2047a0e';

        $this->dnsService->expects($this->once())
            ->method('verifyDomain')
            ->with($certificateDTO->identityProofLocation, $certificateDTO->identityProofKey)
            ->willReturn(false);

        $result = $this->verificationService->verifyCertificate($certificateDTO, $targetHash);

        $this->assertEquals(VerificationService::INVALID_ISSUER, $result);
    }

    public function testVerifyCertificateWithInvalidSignature()
    {
        $certificateDTO = $this->getMockCertificateDTO();
        $invalidTargetHash = 'invalid_hash';

        $this->dnsService->expects($this->once())
            ->method('verifyDomain')
            ->with($certificateDTO->identityProofLocation, $certificateDTO->identityProofKey)
            ->willReturn(true);

        $result = $this->verificationService->verifyCertificate($certificateDTO, $invalidTargetHash);

        $this->assertEquals(VerificationService::INVALID_SIGNATURE, $result);
    }

    public function testVerifyCertificateWithValidData()
    {
        $certificateDTO = $this->getMockCertificateDTO();
        $targetHash = '9fa2a4bf5ed13d52a2e73ad0a9ccc31fa24db32b1eea9ac98b35420e0e59ee3a';

        $this->dnsService->expects($this->once())
            ->method('verifyDomain')
            ->with($certificateDTO->identityProofLocation, $certificateDTO->identityProofKey)
            ->willReturn(true);

        $result = $this->verificationService->verifyCertificate($certificateDTO, $targetHash);

        $this->assertEquals(VerificationService::VERIFIED, $result);
    }

    private function getMockCertificateDTO(): CertificateDTO
    {
        return new CertificateDTO([
            'id' => '63c79bd9303530645d1cca00',
            'name' => 'Certificate of Completion',
            'recipient' => [
                'name' => 'Marty McFly',
                'email' => 'marty.mcfly@gmail.com'
            ],
            'issuer' => [
                'name' => 'Accredify',
                'identityProof' => [
                    'type' => 'DNS-DID',
                    'key' => 'did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller',
                    'location' => 'ropstore.accredify.io'
                ]
            ],
            'issued' => '2022-12-23T00:00:00+08:00'
        ]);
    }
}
