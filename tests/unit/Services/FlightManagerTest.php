<?php

namespace Tests\Unit;

use App\Entity\Flight;
use App\Entity\FlightCollection;
use App\Services\FlightManager;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;

/**
 * todo WIP no enougth time for TDD
 *
 * Class FlightManagerTest
 * @package Tests\Unit
 */
class FlightManagerTest extends TestCase
{
    /**
     * @var FlightManager
     */
    protected $flightManager;

    public function setUp()
    {
        $this->flightManager = new FlightManager();
        $this->flightManager->setFlightCollection($this->createFlightCollection());
    }


    public function testGetFlightCollectionReturnCollection()
    {
        $collection = $this->flightManager->getFlightCollection();
        $this->assertInstanceOf(FlightCollection::class, $collection);
    }

    public function testSetFlightCollectionWillSetAFlightCollectionOnProperty()
    {
        $collection = new FlightCollection();
        $this->flightManager->setFlightCollection($collection);
        $this->assertEquals($collection, $this->flightManager->getFlightCollection());
    }

    public function testCalculateFlightForFoundAirports()
    {
        $flightManagerMock = Mockery::mock('App\Services\FlightManager');
        $flightManagerMock->shouldReceive('calculateFlightsForAirportFleet')->times(2);

        $this->flightManager->calculateFlightForFoundAirports($this->createAirportCollection(), $this->createAirportCollection());
    }

    /**
     * @return Collection
     */
    protected function createAirportCollection()
    {
        $collection = new Collection();
        $collection->add($this->createAirport());
        $collection->add($this->createAirport());

        return $collection;
    }

    /**
     * @return Mockery\MockInterface
     */
    protected function createAirport()
    {
        $airport = Mockery::mock('App\Airport');

        return $airport;
    }

    /**
     * @return FlightCollection
     */
    protected function createFlightCollection()
    {
        $collection = new FlightCollection();
        $collection->appendFlight($this->createFlight());
        $collection->appendFlight($this->createFlight());
        $collection->appendFlight($this->createFlight());

        return $collection;
    }

    protected function createFlight()
    {
        $flight = new Flight();
        $flight->setId(1);
        $flight->setName('Bombardier 101');
        $flight->setCost('50000');
        $flight->setTime(.95);
        $flight->setArrivalAirport('Luxembourg');
        $flight->setDepartureAirport('Paris');

        return $flight;
    }
}