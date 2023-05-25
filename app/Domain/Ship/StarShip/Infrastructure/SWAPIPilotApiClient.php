<?php

declare(strict_types=1);

namespace App\Domain\Ship\StarShip\Infrastructure;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class SWAPIPilotApiClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Call the Pilot API and fetch the pilot data.
     *
     * @throws GuzzleException
     * @throws JsonException
     */
    public function fetchPilot(string $pilotUrl): array
    {
        $response = $this->client->get($pilotUrl);

        return json_decode(
            $response->getBody()->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

}
