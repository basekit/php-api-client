<?php
namespace BaseKit\Api;

use Guzzle\Common\Collection;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Service\Client as GuzzleClient;
use Guzzle\Service\Description\ServiceDescription;

class Client extends GuzzleClient
{
    public static function factory($config = [])
    {
        $default = [];
        $authType  = null;
        if (isset($config['auth_type'])) {
            $authType = $config['auth_type'];
        }
        unset($config['auth_type']);
        if ($authType === AuthType::OAUTH) {
            $required = [
                'base_url',
                'consumer_key',
                'consumer_secret',
                'token',
                'token_secret',
            ];
            $config = Collection::fromConfig($config, $default, $required);
            $client = new self($config->get('base_url'), $config);
            $client->addSubscriber(new OauthPlugin($config->toArray()));
        } else {
            $required = [
                'base_url',
                'request.options'
            ];
            $config = Collection::fromConfig($config, $default, $required);
            $client = new self($config->get('base_url'), $config);
        }

        $client->setDescription(
            ServiceDescription::factory(
                __DIR__ . '/../service/basekit.json'
            )
        );

        return $client;
    }
}
