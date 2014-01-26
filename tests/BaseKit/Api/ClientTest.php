<?php
namespace BaseKitTests\Api;

use BaseKit\Api;
use PHPUnit_Framework_TestCase;

class ClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function factory()
    {
        $client = Api\Client::factory(
            array(
                'base_url' => '',
                'consumer_key' => '',
                'consumer_secret' => '',
                'token' => '',
                'token_secret' => '',
            )
        );
        $this->assertTrue($client instanceof Api\Client);
    }
}
