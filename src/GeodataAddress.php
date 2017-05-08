<?php
declare(strict_types=1);
namespace Eddypouw\GeodataPostalApi;

class GeodataAddress
{
    /**
     * @var string
     */
    private $province;
    /**
     * @var string
     */
    private $municipality;
    /**
     * @var string
     */
    private $city;
    /**
     * @var string
     */
    private $street;
    /**
     * @var string
     */
    private $postal_code;
    /**
     * @var int|null
     */
    private $house_number;

    public function __construct(
        string $province,
        string $municipality,
        string $city,
        string $street,
        string $postal_code,
        int $house_number = null
    ) {
        $this->province = $province;
        $this->municipality = $municipality;
        $this->city = $city;
        $this->street = $street;
        $this->postal_code = $postal_code;
        $this->house_number = $house_number;
    }

    /**
     * @return string
     */
    public function getProvince(): string
    {
        return $this->province;
    }

    /**
     * @return string
     */
    public function getMunicipality(): string
    {
        return $this->municipality;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return int|null
     */
    public function getHouseNumber()
    {
        return $this->house_number;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postal_code;
    }
}