<?php
declare(strict_types=1);
namespace Eddypouw\GeodataPostalApi;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class GeodataAddressRepository
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $postal_code
     * @param int|null $house_number
     * @return GeodataAddress|null
     */
    public function findByPostal(string $postal_code, int $house_number = null)
    {
        if (!$this->isPostalCorrect($postal_code)) {
            throw new \RuntimeException(sprintf("Given postal '%s' is syntactically incorrect", $postal_code));
        }
        $uri = $house_number !== null
            ? sprintf('?zoekterm=%s+%d', $postal_code, $house_number)
            : sprintf('?zoekterm=%s', $postal_code);

        $response = $this->client->request('GET', $uri);
        return $this->createAddressFromResponse($response);
    }

    private function isPostalCorrect(string $postal_code): bool
    {
        $postal_code = str_replace(' ', '', $postal_code);
        return (boolean)preg_match('~^[0-9]{4}[a-zA-Z]{2}$~', $postal_code);
    }

    /**
     * @param ResponseInterface $response
     * @return GeodataAddress|null
     */
    private function createAddressFromResponse(ResponseInterface $response)
    {
        $contents    = $response->getBody()->getContents();
        $xml_element = simplexml_load_string($contents)->children('xls', true)->GeocodeResponseList;

        if (empty($xml_element)) {
            return null;
        }

        $xml_address  = $xml_element->GeocodedAddress->Address;
        $province     = (string) $xml_address->Place[2];
        $municipality = (string) $xml_address->Place[1];
        $city         = (string) $xml_address->Place[0];
        $street       = (string) $xml_address->StreetAddress->Street;
        $postal_code  = (string) $xml_address->PostalCode;
        $house_number = null;

        if (! empty(($building_node = $xml_address->StreetAddress->Building))) {
            $house_number = (int)$xml_address->StreetAddress->Building->attributes()['number'];
        }

        return new GeodataAddress($province, $municipality, $city, $street, $postal_code, $house_number);
    }
}