<?php declare(strict_types=1);

namespace App\Domain\Ship\StarShip;

use App\Domain\Ship\Interface\ShipDataProvider;
use App\Domain\Ship\StarShip\Model\Pilot;
use App\Domain\Ship\StarShip\Model\Starship;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use GuzzleHttp\Client;

class SWAPIShipDataProvider implements ShipDataProvider
{
    private Client $client;
    private array  $allStarships;
    private string $url;
    private int    $limit;

    public function __construct()
    {
        $this->client       = new Client();
        $this->allStarships = [];
        $this->url = "https://swapi.dev/api/starships/";
        $this->limit = 15;
    }

    /**
     * Fetch the ships and return them based on limit number.
     * It is possible to change the limit based on the needs.
     *
     * @throws JsonException
     * @throws GuzzleException
     */
    public function fetchShips(): array
    {
        $starShips = [];
        $this->getStarships($this->limit);
        $filteredStarships = $this->getFilteredStarships( $this->allStarships, $this->limit);

        foreach ($filteredStarships as $starship) {
            $pilots = $this->getPilot($starship['pilots']);
            $starShips[] = new Starship(
                $starship['name'],
                $starship['model'],
                (int) $starship['cargo_capacity'],
                $pilots,
                (int) $starship['max_atmosphering_speed'],
                (int) $starship['crew']
            );
        }

        return $starShips;
    }


    /**
     * Call Starship API and get the ships
     *
     * @throws GuzzleException
     * @throws JsonException
     */
    private function getStarships(int $limit): void
    {
        $count = 0;
        $response = $this->client->get($this->url);
        $data = json_decode( $response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR );

        if (isset($data['results'])){
            foreach ($data['results'] as $starship) {
                $this->allStarships[]= $starship;
                $count = count($this->allStarships);
            }
        }

        if (isset($data['next']) && $count < $limit ) {
            $this->url = $data['next'];
            $this->getStarships($limit);
        }

    }

    /**
     * Filter out the sips based on the limited requested numbers
     */
    private function getFilteredStarships(array $ships, int $limit): array
    {
        $filteredStarships = [];
        $count = 0;

        foreach ($ships as $starship) {
            $filteredStarships[] = $starship;
            $count++;

            if ($count === $limit) {
                break;
            }
        }

        return $filteredStarships;
    }


    /**
     * Call Pilot API and fetch the data and return it based on Pilot class
     *
     * @throws GuzzleException
     * @throws JsonException
     */
    private function getPilot(array $pilotUrls): array
    {
        $pilotList = [];
        foreach ($pilotUrls as $pilotUrl) {
            $pilotResponse = $this->client->get($pilotUrl);
            $pilotData = json_decode(
                $pilotResponse->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            $pilotList[] = new Pilot($pilotData['name'], (int) $pilotData['height']);
        }
        return $pilotList;
    }


    public function setLimit( int $limit ): void
    {
        $this->limit = $limit;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
