<?php
namespace Eddypouw\GeodataPostalApi;

use PHPUnit\Framework\TestCase;

class GeodataAddressTest extends TestCase
{
    public function testConstruct()
    {
        $address = new GeodataAddress('Noord-Holland', 'Zaanstad', 'Zaandam', 'Schansend', '1509AW');

        self::assertSame('Schansend', $address->getStreet());
        self::assertSame('Zaanstad', $address->getMunicipality());
        self::assertSame('Zaandam', $address->getCity());
        self::assertSame('1509AW', $address->getPostalCode());
        self::assertSame('Noord-Holland', $address->getProvince());
        self::assertNull($address->getHouseNumber());

        $address = new GeodataAddress('Noord-Holland', 'Zaanstad', 'Zaandam', 'Schansend', '1509AW', 7);

        self::assertSame('Schansend', $address->getStreet());
        self::assertSame('Zaanstad', $address->getMunicipality());
        self::assertSame('Zaandam', $address->getCity());
        self::assertSame('1509AW', $address->getPostalCode());
        self::assertSame('Noord-Holland', $address->getProvince());
        self::assertSame(7, $address->getHouseNumber());
    }
}
