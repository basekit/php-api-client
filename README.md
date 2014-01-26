BaseKit REST API Client
=======================

[![Build Status](https://secure.travis-ci.org/basekit/php-api-client.png)](http://travis-ci.org/basekit/php-api-client)

A PHP client for [BaseKit]'s REST API. This client provides extensive
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

Contributing
------------

This project adheres to the [PSR2] coding style guide. Checking your
contribution's correctness is easy.

```bash
$ make lint
```

There's a very small unit test suite, using [PHPUnit]. Making sure you haven't
broken any tests is easy too.

```bash
$ make test
```

License
-------

To be determined.

[BaseKit]: http://basekit.com/
[PHPUnit]: http://phpunit.de/
[PSR2]: http://www.php-fig.org/psr/psr-2/
