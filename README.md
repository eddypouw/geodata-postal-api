# Geodata postal API
PHP client to find Dutch addresses based on postal code and house number.

This library is based on the open geodata from [https://nationaalgeoregister.nl](https://nationaalgeoregister.nl).

[![Build Status](https://travis-ci.org/eddypouw/geodata-postal-api.svg?branch=master)](https://travis-ci.org/eddypouw/geodata-postal-api)

Usage
-----
Install the latest version via [composer](https://getcomposer.org/):
```bash
php composer.phar require eddypouw/geodata-postal-api
```

Example:
```php
<?php
require_once('vendor/autoload.php');

$client      = new \GuzzleHttp\Client(['base_uri' => 'https://geodata.nationaalgeoregister.nl/geocoder/Geocoder']);
$geodata_api = new \Eddypouw\GeodataPostalApi\GeodataAddressRepository($client);

$response = $geodata_api->findByPostal('1509AW', 7);

print $response->getStreet() . ' ' . $response->getHouseNumber() . "\n";
print $response->getPostalCode() . ' ' . $response->getMunicipality() . "\n";
```
Requirements
------------

PHP 7.0.x or above.

License
-------

This library is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.