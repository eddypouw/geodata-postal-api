<?php
declare(strict_types=1);
namespace Eddypouw\GeodataPostalApi;

use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class GeodataAddressRepositoryTest extends TestCase
{
    private $client;

    /**
     * @var GeodataAddressRepository
     */
    private $geodata_address_repository;

    protected function setUp()
    {
        $this->client = $this->prophesize(ClientInterface::class);

        $this->geodata_address_repository = new GeodataAddressRepository($this->client->reveal());
    }

    public function testFindByPostalIncorrectPostal()
    {
        $this->expectException(\RuntimeException::class);
        $this->geodata_address_repository->findByPostal('12345AA');
    }

    public function testFindByPostalNoResult()
    {
        $response = $this->prophesize(ResponseInterface::class);
        $stream   = $this->prophesize(StreamInterface::class);
        $stream->getContents()->willReturn(
            '<xls:GeocodeResponse xmlns:xls="http://www.opengis.net/xls" xmlns:gml="http://www.opengis.net/gml"/>'
        );
        $response->getBody()->willReturn($stream);
        $this->client->request('GET', '?zoekterm=1234AA')->willReturn($response);

        self::assertNull($this->geodata_address_repository->findByPostal('1234AA'));
    }

    public function testFindByPostalOnlyPostal()
    {
        $response = $this->prophesize(ResponseInterface::class);
        $stream   = $this->prophesize(StreamInterface::class);
        $stream->getContents()->willReturn(
            '<xls:GeocodeResponse xmlns:xls="http://www.opengis.net/xls" xmlns:gml="http://www.opengis.net/gml">
                <xls:GeocodeResponseList numberOfGeocodedAddresses="1">
                    <xls:GeocodedAddress>
                        <gml:Point srsName="EPSG:28992">
                            <gml:pos dimension="2">116549.742 498573.7665</gml:pos>
                        </gml:Point>
                        <xls:Address countryCode="NL">
                            <xls:StreetAddress>
                                <xls:Street>Schansend</xls:Street>
                            </xls:StreetAddress>
                            <xls:Place type="MunicipalitySubdivision">Zaandam</xls:Place>
                            <xls:Place type="Municipality">Zaanstad</xls:Place>
                            <xls:Place type="CountrySubdivision">Noord-Holland</xls:Place>
                            <xls:PostalCode>1509AW</xls:PostalCode>
                        </xls:Address>
                    </xls:GeocodedAddress>
                </xls:GeocodeResponseList>
            </xls:GeocodeResponse>'
        );
        $response->getBody()->willReturn($stream);
        $this->client->request('GET', '?zoekterm=1509AW')->willReturn($response);

        $expected = new GeodataAddress('Noord-Holland', 'Zaanstad', 'Zaandam', 'Schansend', '1509AW');

        self::assertEquals($expected, $this->geodata_address_repository->findByPostal('1509AW'));
    }

    public function testFindByPostal()
    {
        $response = $this->prophesize(ResponseInterface::class);
        $stream   = $this->prophesize(StreamInterface::class);
        $stream->getContents()->willReturn(
            '<xls:GeocodeResponse xmlns:xls="http://www.opengis.net/xls" xmlns:gml="http://www.opengis.net/gml">
                <xls:GeocodeResponseList numberOfGeocodedAddresses="1">
                    <xls:GeocodedAddress>
                        <gml:Point srsName="EPSG:28992">
                            <gml:pos dimension="2">116549.742 498573.7665</gml:pos>
                        </gml:Point>
                        <xls:Address countryCode="NL">
                            <xls:StreetAddress>
                                <xls:Building number="7"/>
                                <xls:Street>Schansend</xls:Street>
                            </xls:StreetAddress>
                            <xls:Place type="MunicipalitySubdivision">Zaandam</xls:Place>
                            <xls:Place type="Municipality">Zaanstad</xls:Place>
                            <xls:Place type="CountrySubdivision">Noord-Holland</xls:Place>
                            <xls:PostalCode>1509AW</xls:PostalCode>
                        </xls:Address>
                    </xls:GeocodedAddress>
                </xls:GeocodeResponseList>
            </xls:GeocodeResponse>'
        );
        $response->getBody()->willReturn($stream);
        $this->client->request('GET', '?zoekterm=1509AW+7')->willReturn($response);

        $expected = new GeodataAddress('Noord-Holland', 'Zaanstad', 'Zaandam', 'Schansend', '1509AW', 7);

        self::assertEquals($expected, $this->geodata_address_repository->findByPostal('1509AW', 7));
    }
}
