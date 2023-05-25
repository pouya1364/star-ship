<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Ship\StarShip\Model\Starship;
use App\Domain\Ship\StarShip\SWAPIShipDataProvider;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller as BaseController;
use JsonException;

class StarShipController extends BaseController
{
    public function __construct(private SWAPIShipDataProvider $dataProvider)
    {
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function starship(): View
    {
        $starShips = $this->dataProvider->fetchShips();
        $ships = StarShip::sortBySpeedDescending($starShips);

        // Get the first element of sorted ships based on speed
        $fastestShip = reset($ships);

        return view('starships', compact('ships', 'fastestShip'));
    }
}
