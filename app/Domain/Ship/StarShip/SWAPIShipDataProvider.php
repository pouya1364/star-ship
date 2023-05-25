<?php

declare(strict_types=1);

namespace App\Domain\Ship\StarShip;

use App\Domain\Ship\Interface\ShipDataProvider;
use App\Domain\Ship\StarShip\Model\Pilot;
use App\Domain\Ship\StarShip\Model\Starship;
use App\Domain\Ship\StarShip\Infrastructure\SWAPIPilotApiClient;
use App\Domain\Ship\StarShip\Infrastructure\SWAPIShipApiClient;
use App\Exceptions\SWAPIShipException;
use JsonException;

class SWAPIShipDataProvider implements ShipDataProvider
{
    // Limit the number of ships in request
    private int $limit;

    public function __construct(
        private SWAPIShipApiClient $shipApiClient,
        private SWAPIPilotApiClient $pilotApiClient
    ) {
        $this->limit = 15;
    }

    /**
     * Fetch the ships and return them based on the limit number.
     * It is possible to change the limit based on the needs.
     *
     * @throws JsonException
     */
    public function fetchShips(): array
    {
        $starShips = [];
        $allStarships = $this->shipApiClient->fetchStarships();
        $filteredStarships = array_slice($allStarships, 0, $this->limit);

        foreach ($filteredStarships as $starship) {
            $pilots = $this->getPilots($starship['pilots']);
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
     * Fetch the pilots and return them as Pilot objects.
     *
     * @throws JsonException
     */
    private function getPilots(array $pilotUrls): array
    {
        $pilotList = [];

        foreach ($pilotUrls as $pilotUrl) {
            $pilotData = $this->pilotApiClient->fetchPilot($pilotUrl);

            $pilotList[] = new Pilot($pilotData['name'], (int) $pilotData['height']);
        }

        return $pilotList;
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
