<?php

namespace App\DTO;
use Illuminate\Support\Arr;

class CertificateDTO
{
    public string $id;
    public string $name;
    public string $recipientName;
    public string $recipientEmail;
    public string $issuerName;
    public string $identityProofType;
    public string $identityProofKey;
    public string $identityProofLocation;
    public string $issued;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->recipientName = $data['recipient']['name'];
        $this->recipientEmail = $data['recipient']['email'];
        $this->issuerName = $data['issuer']['name'];
        $this->identityProofType = $data['issuer']['identityProof']['type'];
        $this->identityProofKey = $data['issuer']['identityProof']['key'];
        $this->identityProofLocation = $data['issuer']['identityProof']['location'];
        $this->issued = $data['issued'];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'recipient' => [
                'name' => $this->recipientName,
                'email' => $this->recipientEmail,
            ],
            'issuer' => [
                'name' => $this->issuerName,
                'identityProof' => [
                    'type' => $this->identityProofType,
                    'key' => $this->identityProofKey,
                    'location' => $this->identityProofLocation,
                ],
            ],
            'issued' => $this->issued,
        ];
    }

    public function toFlatArray(): array
    {
        return Arr::dot($this->toArray());
    }
}
