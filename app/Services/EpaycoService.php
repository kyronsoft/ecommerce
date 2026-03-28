<?php

namespace App\Services;

use Epayco\Epayco;
use Illuminate\Support\Facades\Http;

class EpaycoService
{
    protected Epayco $client;

    public function __construct()
    {
        $this->client = new Epayco([
            'apiKey' => config('epayco.public_key'),
            'privateKey' => config('epayco.private_key'),
            'lenguage' => config('epayco.lang'),
            'test' => config('epayco.test'),
        ]);
    }

    public function client(): Epayco
    {
        return $this->client;
    }

    public function createToken(array $data)
    {
        return $this->client->token->create($data);
    }

    public function createCustomer(array $data)
    {
        return $this->client->customer->create($data);
    }

    public function createPlan(array $data)
    {
        return $this->client->plan->create($data);
    }

    public function getTransactionByReference(string $reference): array
    {
        $baseUrl = rtrim((string) config('epayco.validation_url'), '/').'/';

        $response = Http::acceptJson()
            ->connectTimeout(3)
            ->timeout(5)
            ->get($baseUrl.$reference);

        if (! $response->successful()) {
            return [];
        }

        $payload = $response->json();

        if (! is_array($payload)) {
            return [];
        }

        $payload = $payload['data'] ?? $payload;

        if (isset($payload[0]) && is_array($payload[0])) {
            $payload = $payload[0];
        }

        return is_array($payload) ? $payload : [];
    }
}
