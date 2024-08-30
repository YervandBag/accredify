<?php

namespace App\Services;

use Cache;
use Illuminate\Support\Facades\Http;

class DnsService
{
    const DNS_RESOLVE_BASE_URL = 'https://dns.google/resolve';

    /**
     * Verify DNS identityProof
     * @param string $location domain name
     * @param string $key wallet address
     * @return bool
     */
    public function verifyDomain(string $location, string $key): bool
    {
        $isVerified = false;
        $cacheKey = "dns-check-$location-$key";
        $cachedValue = Cache::get($cacheKey);

        if ($cachedValue !== null) {
            return $cachedValue;
        }

        $response = Http::get(self::DNS_RESOLVE_BASE_URL, [
            'name' => $location,
            'type' => 'TXT',
        ]);

        if (!$response->ok()) {
            return $isVerified;
        }

        $answers = $response->object()->Answer;
        foreach ($answers as $answer) {
            if ($answer->data && str_contains($answer->data, $key)) {
                $isVerified = true;
                break;
            }
        }

        // 60 seconds
        Cache::add($cacheKey, $isVerified, 60);

        return $isVerified;
    }
}