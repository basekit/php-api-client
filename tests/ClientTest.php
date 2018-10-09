<?php
namespace BaseKitTests\Api;

use BaseKit\Api;
use Guzzle\Common\Exception\InvalidArgumentException;
use Guzzle\Service\Command\OperationCommand;
use PHPUnit_Framework_TestCase;

class ClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function factoryMissing()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $client = Api\Client::factory([]);
        $this->assertTrue($client instanceof Api\Client);
    }

    /**
     * @test
     */
    public function factoryBasic()
    {
        $client = Api\Client::factory(
            [
                'base_url'        => '',
                'request.options' => '',
                'auth_type'       => Api\AuthType::BASIC
            ]
        );
        $this->assertTrue($client instanceof Api\Client);
        return $client;
    }

    /**
     * @test
     */
    public function factoryOauth()
    {
        $client = Api\Client::factory(
            [
                'base_url'        => '',
                'consumer_key'    => '',
                'consumer_secret' => '',
                'token'           => '',
                'token_secret'    => '',
                'auth_type'       => Api\AuthType::OAUTH
            ]
        );
        $this->assertTrue($client instanceof Api\Client);
        return $client;
    }

    /**
     * @test
     * @depends factoryBasic
     */
    public function loadsServiceDescription($client)
    {
        $command = $client->getCommand('CreateSite');
        $this->assertTrue($command instanceof OperationCommand);
    }
}
