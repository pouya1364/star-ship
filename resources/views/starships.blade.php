<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }
</style>

<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Model</th>
        <th>Cargo Capacity</th>
        <th>Speed Difference</th>
        <th>Pilots</th>
        <th>Crew Size</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($ships as $ship)
        <tr>
            <td>{{ $ship->getName() }}</td>
            <td>{{ $ship->getModel() }}</td>
            <td>{{ $ship->getCargoCapacity() }}</td>
            <td>{{ $ship->calculateSpeedDifference($fastestShip) }}% slower</td>

            @if (!empty($ship->getPilots()))
                <td>
                    @foreach ($ship->getPilots() as $pilot)
                        <div>
                            <span>Name: {{ $pilot->getName() }}</span>
                            <span>Height: {{ $pilot->getHeight() }}</span>
                        </div>
                    @endforeach
                </td>
            @else
                <td>No pilots</td>
            @endif

            <td>Crew size: {{ $ship->getCrew() }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
