<?php

declare(strict_types=1);

namespace App\Domain\Ship\StarShip\Infrastructure;

use App\Exceptions\SWAPIShipException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class SWAPIShipApiClient
{
    private Client $client;
    private string $url;
    private int $limit;

    public function __construct(int $limit = 15)
    {
        $this->client = new Client();
        $this->url = "https://swapi.dev/api/starships/";
        $this->limit = $limit;
    }

    /**
     * Call the Starship API and fetch the starships.
     *
     * @throws GuzzleException
     * @throws JsonException
     */
    public function fetchStarships(): array
    {
        $starships = [];
        $count = 0;

        do {
            $response = $this->client->get($this->url);
            $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

            if (isset($data['results'])) {
                foreach ($data['results'] as $starship) {
                    $starships[] = $starship;
                    $count++;

                    if ($count === $this->limit) {
                        return $starships;
                    }
                }
            }

            $this->url = $data['next'];
        } while ($this->url !== null);

        return $starships;
    }

    public function setLimit(int $limit): void
    {
        if ($limit > 0) {
            $this->limit = $limit;
        } else {
            throw new SWAPIShipException( "The limit number should not be negative");
        }
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
