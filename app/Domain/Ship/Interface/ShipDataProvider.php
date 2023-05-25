<?php

namespace App\Domain\Ship\Interface;

interface ShipDataProvider
{
    public function fetchShips(): array;
}
