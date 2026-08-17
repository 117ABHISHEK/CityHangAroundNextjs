<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Exception;

class CloudflareService
{
    protected string $apiToken;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiToken = config('services.cloudflare.token');
        $this->baseUrl = 'https://api.cloudflare.com/client/v4';
    }

    /**
     * Base request with headers
     */
    protected function request(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withToken($this->apiToken)
            ->acceptJson()
            ->baseUrl($this->baseUrl);
    }

    /**
     * Get all zones
     */
    public function getZones(): array
    {
        $response = $this->request()->get('/zones');

        return $this->handleResponse($response);
    }

    /**
     * Get single zone details
     */
    public function getZone(string $zoneId): array
    {
        $response = $this->request()->get("/zones/{$zoneId}");

        return $this->handleResponse($response);
    }

    /**
     * Purge entire cache for a zone
     */
    public function purgeEverything(string $zoneId): array
    {
        $response = $this->request()->post("/zones/{$zoneId}/purge_cache", [
            'purge_everything' => true,
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Purge specific files from cache
     */
    public function purgeFiles(string $zoneId, array $files): array
    {
        $response = $this->request()->post("/zones/{$zoneId}/purge_cache", [
            'files' => $files,
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Create DNS record
     */
    public function createDnsRecord(string $zoneId, array $data): array
    {
        $response = $this->request()->post("/zones/{$zoneId}/dns_records", $data);

        return $this->handleResponse($response);
    }

    /**
     * Delete DNS record
     */
    public function deleteDnsRecord(string $zoneId, string $recordId): array
    {
        $response = $this->request()->delete("/zones/{$zoneId}/dns_records/{$recordId}");

        return $this->handleResponse($response);
    }

    /**
     * Handle API response
     */
    protected function handleResponse(Response $response): array
    {
        if ($response->successful()) {
            return $response->json();
        }

        throw new Exception(
            'Cloudflare API Error: ' . $response->body(),
            $response->status()
        );
    }
}