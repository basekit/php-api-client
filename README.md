# BaseKit REST API Client

A PHP client for [BaseKit](http://basekit.com/)'s REST API. This client will provide documentation of the services available from the BaseKit API, describing URIs, HTTP methods and input parameters.

## Installation

The recommended way of including this package in your project is by using Composer. Add it to the `require` section of your project's `composer.json`.

```bash
composer require basekit/php-api-client
```

## Usage

```php
use BaseKit\Api\AuthType;
use BaseKit\Api\ClientFactory;

$client = ClientFactory::create(
    [
        'base_uri' => 'http://api.testing.com',
        'username' => 'foo',
        'password' => 'bar',
    ],
    AuthType::BASIC, // defaults to basic auth
);

$createSite = $client->getCommand(
    'CreateSite',
    [
        'accountHolderRef' => 123,
        'brandRef' => 789,
        'domain' => 'test.example.org',
    ]
);

$client->execute($createSite);
```

## Testing

Feed an optional `handler` into the config of `clientFactory` to control the responses from the http client.

```php
use BaseKit\Api\ClientFactory;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

$client = ClientFactory::create([
    'base_uri' => 'https://api.testing.com',
    'username' => 'foo',
    'password' => 'bar',
    'handler' => HandlerStack::create(
        new MockHandler([
            new Response(404, [], '"Hello, World! This is a test response."'),
        ])
    ) ,
]);

$createSite = $client->getCommand(
    'CreateSite',
    [
        'accountHolderRef' => 123,
        'brandRef' => 789,
        'domain' => 'test.example.org',
    ]
);

$client->execute($createSite); // Throws a 404 CommandClientException
```

## License

This software is released under the [MIT License](http://www.opensource.org/licenses/MIT).
