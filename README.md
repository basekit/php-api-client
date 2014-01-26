BaseKit REST API Client
=======================

A PHP client for BaseKit's REST API. This client provides extensive
documentation of the services available from the BaseKit API, describing URIs,
HTTP methods and input parameters.

Installation
------------

The recommended way of including this package in your project is by using
Composer. Add it to the `require` section of your project's `composer.json`.

    "basekit/php-api-client": "dev-master"

Usage
-----

```php
<?php

require 'vendor/autoload.php';
use BaseKit\Api\Client;

$client = Client::factory(
    array(
        'base_url'        => 'http://api.example.org',
        'consumer_key'    => '1234567890',
        'consumer_secret' => 'qwertyuiop',
        'token'           => 'asdfghjkl',
        'token_secret'    => 'zxcvbnm',
    )
);

$getPages = $client->getCommand('GetSitesPages', array('siteRef' => 123));
$response = $getPages->execute();
print_r($response);
```

License
-------

To be determined.
