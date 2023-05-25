<?php declare(strict_types=1);

namespace App\Domain\Ship\StarShip\Model;

class Pilot
{
    public function __construct(private string $name, private int $height)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}
