<?php declare(strict_types=1);

namespace App\Domain\Ship\StarShip\Model;

use App\Domain\Ship\Interface\CargoTransport;
use Illuminate\Testing\Assert;

class Starship implements CargoTransport
{
    public function __construct(
        private string $name,
        private string $model,
        private int    $cargoCapacity,
        private array  $pilots,
        private int    $speed,
        private int    $crew,
    )
    {
    }

    /**
     * Add more cargo to the ship and reduce the current capacity
     */
    public function addCargo(int $cargo): int
    {
        if ($cargo <= $this->cargoCapacity) {
            $this->cargoCapacity -= $cargo;
            echo "Cargo added to {$this->name}. Remaining capacity: {$this->cargoCapacity} tons.\n";
        } else {
            echo "{$this->name} cannot accommodate that much cargo.\n";
        }

        return $this->cargoCapacity;
    }

    /**
     * Calculate the speed difference between the fastest ship with current ship
     */
    public function calculateSpeedDifference(Starship $fastestShip): float
    {
        $speedDifInPercentage = (($fastestShip->getSpeed() - $this->getSpeed()) / $fastestShip->getSpeed()) * 100;
        return round($speedDifInPercentage, 2);
    }

    /**
     * Sort the all fetched ships by their speed with descending order
     */
    public static function sortBySpeedDescending( array $starships): array
    {
        usort($starships, static function ($a, $b) {
            Assert::assertInstanceOf(self::class, $a, 'The provided array is not an instance of '.self::class);
            Assert::assertInstanceOf(self::class, $b, 'The provided array is not an instance of '.self::class);

            return $b->getSpeed() - $a->getSpeed();
        });

        return $starships;
    }


    public function getCrew(): int
    {
        return $this->crew;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getCargoCapacity(): int
    {
        return $this->cargoCapacity;
    }

    public function getPilots(): array
    {
        return $this->pilots;
    }

    public function getSpeed(): int
    {
        return $this->speed;
    }

}
