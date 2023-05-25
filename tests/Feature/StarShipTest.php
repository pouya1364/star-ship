<?php

namespace Tests\Feature;

use App\Domain\Ship\StarShip\Infrastructure\SWAPIPilotApiClient;
use App\Domain\Ship\StarShip\Infrastructure\SWAPIShipApiClient;
use App\Domain\Ship\StarShip\Model\Starship;
use App\Domain\Ship\StarShip\SWAPIShipDataProvider;
use Tests\TestCase;

class StarShipTest extends TestCase
{
    private SWAPIShipDataProvider $shipDataProvider;
    private SWAPIPilotApiClient   $pilotApiClient;
    private SWAPIShipApiClient    $shipApiClient;

    protected function setUp(): void
    {
        $this->pilotApiClient = new SWAPIPilotApiClient();
        $this->shipApiClient  = new SWAPIShipApiClient();
        $this->shipDataProvider= new SWAPIShipDataProvider($this->shipApiClient, $this->pilotApiClient);
        parent::setUp();
    }

    public function testFetchData(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->shipDataProvider->setLimit( 1);
        $ships = $this->shipDataProvider->fetchShips();

        $this->assertNotEmpty($ships);
        $shipObject = $ships[0];
        $this->assertInstanceOf(Starship::class, $shipObject);

        $prevCapacity = $shipObject->getCargoCapacity();
        $shipObject->addCargo(2000);

        $this->assertEquals($prevCapacity-2000, $shipObject->getCargoCapacity());

        $shipObject->addCargo($prevCapacity+20000);
    }

    public function testSortBySpeed(): void
    {
        $highestSpeed = 0;
        $this->shipDataProvider->setLimit( 3);
        $ships = $this->shipDataProvider->fetchShips();

        foreach ($ships as $ship) {
            $allSpeeds[]  = $ship->getSpeed();
            $highestSpeed = max($allSpeeds);

        }

        $sortedShips = StarShip::sortBySpeedDescending($ships);
        $sortedSpeed = $sortedShips[0]->getSpeed();

        $this->assertEquals($highestSpeed, $sortedSpeed);
    }
}
