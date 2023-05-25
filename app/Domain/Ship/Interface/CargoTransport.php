<?php

namespace App\Domain\Ship\Interface;

interface CargoTransport
{
    public function addCargo(int $cargo): int;
    public function getCargoCapacity(): int;
}
