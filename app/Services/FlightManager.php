<?php

namespace App\Services;

use App\Aircraft;
use App\Airport;
use App\Entity\Flight;
use App\Entity\FlightCollection;
use App\Helper\CoordinatesTransfomer;
use Illuminate\Database\Eloquent\Collection;
use League\Geotools\Coordinate\Coordinate;

class FlightManager
{
    /**
     * @var FlightCollection
     */
    protected $flightCollection = null;

    /**
     * @return FlightCollection
     */
    public function getFlightCollection()
    {
        return $this->flightCollection;
    }

    /**
     * @param FlightCollection $flightCollection
     *
     * @return $this
     */
    public function setFlightCollection(FlightCollection $flightCollection)
    {
        $this->flightCollection = $flightCollection;

        return $this;
    }

    /**
     * @param Collection $departuresAirports
     * @param Collection $destinationAirports
     */
    public function calculateFlightForFoundAirports(Collection $departuresAirports, Collection $destinationAirports)
    {
        $this->setFlightCollection(new FlightCollection());

        foreach ($departuresAirports as $departureAirport) {
            $this->calculateFlightsForAirportFleet($departureAirport, $destinationAirports);
        }
    }

    public function calculateFlightsForAirportFleet(Airport $departureAirport, Collection $destinationAirports)
    {
        foreach ($departureAirport->getAircrafts()->get() as $aircraft) {
            $this->calculateFlightByAircraftForDestinationsAirports($aircraft, $departureAirport, $destinationAirports);
        }
    }

    /**
     * @param Airport $departureAirport
     * @param Airport $destinationAirport
     * @param Aircraft $aircraft
     */
    public function calculateFlight(Airport $departureAirport, Airport $destinationAirport, Aircraft $aircraft)
    {
        $flight = new Flight($aircraft->getAttributes());
        $flight->setTime($this->calculateFlightDuration($departureAirport, $destinationAirport, $aircraft));
        $flight->setCost($this->calculateFlightCost($flight->getTime(), $aircraft));
        $flight->setDepartureAirport($departureAirport->getAttribute('name'));
        $flight->setArrivalAirport($destinationAirport->getAttribute('name'));

        $this->getFlightCollection()->appendFlight($flight);
    }

    /**
     * @param Aircraft $aircraft
     * @param Airport $departureAirport
     * @param Collection $destinationAirports
     **/
    public function calculateFlightByAircraftForDestinationsAirports(Aircraft $aircraft, Airport $departureAirport, Collection $destinationAirports)
    {
        foreach ($destinationAirports as $destinationAirport) {
            $this->calculateFlight($departureAirport, $destinationAirport, $aircraft);
        }
    }

    /**
     * @param Airport $departureAirport
     * @param Airport $destinationAirport
     *
     * @return float
     */
    public function calculateAirportsDistance(Airport $departureAirport, Airport $destinationAirport): float
    {
        return CoordinatesTransfomer::calculateDistance(
            new Coordinate([$departureAirport->getAttribute('latitude'), $departureAirport->getAttribute('longitude')]),
            new Coordinate([$destinationAirport->getAttribute('latitude'), $destinationAirport->getAttribute('longitude')])
        );
    }

    /**
     * @param Airport $departureAirport
     * @param Airport $destinationAirport
     * @param Aircraft $aircraft
     *
     * @return float
     */
    public function calculateFlightDuration(Airport $departureAirport, Airport $destinationAirport, Aircraft $aircraft): float
    {
        $distance = $this->calculateAirportsDistance($departureAirport, $destinationAirport);
        $speed = $aircraft->getAttribute('speed');

        return !empty($speed) ? $distance / $speed : 0;
    }

    /**
     * @param float $duration
     * @param Aircraft $aircraft
     *
     * @return float
     */
    public function calculateFlightCost(float $duration, Aircraft $aircraft): float
    {
        return $duration * ($aircraft->getAttribute('hourly_cost') ?? 0);
    }
}