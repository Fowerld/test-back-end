<?php

namespace App\Entity;

class FlightCollection
{
    /**
     * @var Flight[]
     */
    protected $flights = [];

    /**
     * @return array
     */
    public function getFlights(): array
    {
        return $this->flights;
    }

    /**
     * @param array $flights
     * @return FlightCollection
     */
    public function setFlights(array $flights): FlightCollection
    {
        $this->flights = $flights;

        return $this;
    }

    /**
     * @param Flight $flight
     * @return $this
     */
    public function appendFlight(Flight $flight): FlightCollection
    {
        $this->setFlights(array_unique(array_merge($this->getFlights(), [$flight]), SORT_REGULAR));

        return $this;
    }
}