<?php

namespace BaseKit\Api;

use Guzzle\Common\Collection;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Service\Client as GuzzleClient;
use Guzzle\Service\Description\ServiceDescription;

class Client extends GuzzleClient
{
    public static function factory($config = array())
    {
        $default = array();

        $required = array(
            'base_url',
            'consumer_key',
            'consumer_secret',
            'token',
            'token_secret',
        );

        $config = Collection::fromConfig($config, $default, $required);

        $client = new self($config->get('base_url'), $config);
        $client->addSubscriber(new OauthPlugin($config->toArray()));

        $client->setDescription(
            ServiceDescription::factory(
                __DIR__ . '/../../../service/basekit.json'
            )
        );

        return $client;
    }
}
