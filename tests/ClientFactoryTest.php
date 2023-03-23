<?php

declare(strict_types=1);

namespace BaseKitTests\Api;

use BaseKit\Api\AuthType;
use BaseKit\Api\ClientFactory;
use GuzzleHttp\Command\Command;
use GuzzleHttp\Command\Exception\CommandClientException;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ClientFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function throwsInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $client = ClientFactory::create([ 'garbage' => 'foo' ]);
        $this->assertTrue($client instanceof GuzzleClient);
    }

    /**
     * @test
     */
    public function throwsInvalidArgumentExceptionWithInvalidAuthType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $client = ClientFactory::create(
            [
                'base_uri' => 'http://api.testing.com',
                'username' => '',
                'password' => '',
            ],
            'garbage'
        );
    }

    /**
     * @test
     */
    public function returnsBasicAuthClient(): void
    {
        $client = ClientFactory::create(
            [
                'base_uri' => 'http://api.testing.com',
                'username' => '',
                'password' => '',
            ],
        );
        $this->assertTrue($client instanceof GuzzleClient);
    }

    /**
     * @test
     */
    public function returnsOAuthClient(): void
    {
        $client = ClientFactory::create(
            [
                'base_uri' => 'http://api.example.org',
                'consumer_key' => '1234567890',
                'consumer_secret' => 'qwertyuiop',
                'token' => 'asdfghjkl',
                'token_secret' => 'zxcvbnm',
            ],
            AuthType::OAUTH
        );
        $this->assertTrue($client instanceof GuzzleClient);
    }

    /**
     * @test
     */
    public function executesMockCommandSuccessfully(): void
    {
        $mockHandler = $this->createMockHandler(200);

        $client = ClientFactory::create([
            'base_uri' => 'https://api.testing.com',
            'username' => 'foo',
            'password' => 'bar',
            'handler' => $mockHandler,
        ]);

        $createSite = $client->getCommand(
            'CreateSite',
            [
                'accountHolderRef' => 123,
                'brandRef' => 789,
                'domain' => 'test.example.org',
            ]
        );

        $this->assertTrue($createSite instanceof Command);

        $result = $client->execute($createSite);

        $this->assertEquals(
            'Hello, World! This is a test response.',
            $result['response']
        );
    }

    /**
     * @test
     */
    public function executesMockErrorSuccessfully(): void
    {
        $mockResponseCode = 418;
        $mockHandler = $this->createMockHandler($mockResponseCode);

        $client = ClientFactory::create(
            [
                'base_uri' => 'https://api.testing.com',
                'consumer_key' => '1234567890',
                'consumer_secret' => 'qwertyuiop',
                'token' => 'asdfghjkl',
                'token_secret' => 'zxcvbnm',
                'handler' => $mockHandler,
            ],
            AuthType::OAUTH
        );

        $createSite = $client->getCommand(
            'CreateSite',
            [
                'accountHolderRef' => 123,
                'brandRef' => 789,
                'domain' => 'test.example.org',
            ]
        );

        $this->assertTrue($createSite instanceof Command);

        try {
            $client->execute($createSite);
        } catch (CommandClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();

            if ($statusCode !== $mockResponseCode) {
                $this->fail(sprintf(
                    'Expected a mocked %d response code, got %s',
                    $mockResponseCode,
                    $statusCode
                ));
            }

            return;
        }

        $this->fail('Expected a mocked CommandClientException');
    }

    private function createMockHandler(int $responseCode): HandlerStack
    {
        $jsonResponse = '"Hello, World! This is a test response."'; // ensure this is valid json

        return HandlerStack::create(
            new MockHandler([
                new Response($responseCode, [], $jsonResponse),
            ])
        );
    }
}
