<?php

namespace App\Entity;

use App\Entity\Helper\EntityInterface;
use App\Entity\Helper\HydratableEntityTrait;
use App\Entity\Helper\ObjectTransformerTrait;
use App\Entity\Helper\PropertyStringStylesInterface;

class Flight implements \JsonSerializable, EntityInterface, PropertyStringStylesInterface
{
    use HydratableEntityTrait;
    use ObjectTransformerTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var float
     */
    protected $time = 0;

    /**
     * @var float
     */
    protected $cost = 0;

    /**
     * @var string
     */
    protected $departureAirport;

    /**
     * @var string
     */
    protected $arrivalAirport;

    public function __construct(array $data = [])
    {
        if (count($data)) {
            $this->hydrate($data);
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Flight
     */
    public function setId(int $id): Flight
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Flight
     */
    public function setName(string $name): Flight
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float
     */
    public function getTime(): float
    {
        return $this->time;
    }

    /**
     * @param float $time
     * @return Flight
     */
    public function setTime(float $time): Flight
    {
        $this->time = $time;
        return $this;
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     * @return Flight
     */
    public function setCost(float $cost): Flight
    {
        $this->cost = $cost;
        return $this;
    }

    /**
     * @return string
     */
    public function getDepartureAirport(): string
    {
        return $this->departureAirport;
    }

    /**
     * @param string $departureAirport
     * @return Flight
     */
    public function setDepartureAirport(string $departureAirport): Flight
    {
        $this->departureAirport = $departureAirport;
        return $this;
    }

    /**
     * @return string
     */
    public function getArrivalAirport(): string
    {
        return $this->arrivalAirport;
    }

    /**
     * @param string $arrivalAirport
     * @return Flight
     */
    public function setArrivalAirport(string $arrivalAirport): Flight
    {
        $this->arrivalAirport = $arrivalAirport;
        return $this;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray(PropertyStringStylesInterface::SNAKE_CASE);
    }
}
