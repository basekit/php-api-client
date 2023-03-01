<?php

declare(strict_types=1);

namespace BaseKit\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use InvalidArgumentException;

class ClientFactory
{
    public static function create(array $config, string $authType = AuthType::BASIC): GuzzleClient
    {
        self::checkRequiredConfigOptions($config, $authType);

        $guzzleClient = self::createGuzzleClient($config, $authType);

        return $guzzleClient;
    }

    private static function checkRequiredConfigOptions(array $proividedConfig, string $authType): void
    {
        $requiredConfigOptions = self::getRequiredConfigOptions($authType);

        foreach ($requiredConfigOptions as $requiredConfigOption) {
            if (!isset($proividedConfig[$requiredConfigOption])) {
                throw new InvalidArgumentException(sprintf(
                    'Required config: %s',
                    implode(', ', $requiredConfigOptions)
                ));
            }
        }
    }

    private static function createGuzzleClient(array $config, string $authType): GuzzleClient
    {
        $httpClient = match ($authType) {
            AuthType::BASIC => self::createBasicHttpClient($config),
            AuthType::OAUTH => self::createOAuthHttpClient($config),
            default => throw new InvalidArgumentException("Unknown auth type: $authType")
        };
        $serviceDescriptionContents = (new ServiceLoader())->load();
        $serviceDescription = new Description($serviceDescriptionContents);

        return new GuzzleClient(
            $httpClient,
            $serviceDescription,
        );
    }

    private static function createBasicHttpClient(array $config): ClientInterface
    {
        $config['auth'] = [
            $config['username'],
            $config['password'],
        ];

        return new Client($config);
    }

    private static function createOAuthHttpClient(array $config): ClientInterface
    {
        $stack = $config['handler'] ?? HandlerStack::create();
        $stack->push(new Oauth1([
            'consumer_key' => $config['consumer_key'],
            'consumer_secret' => $config['consumer_secret'],
            'token' => $config['token'],
            'token_secret' => $config['token_secret'],
        ]));
        $config['handler'] = $stack;
        $config['auth'] = 'oauth';

        return new Client($config);
    }

    private static function getRequiredConfigOptions(string $authType): array
    {
        return match ($authType) {
            AuthType::OAUTH => [
                'base_uri',
                'consumer_key',
                'consumer_secret',
                'token',
                'token_secret',
            ],
            AuthType::BASIC => [
                'base_uri',
                'username',
                'password',
            ],
            default => throw new InvalidArgumentException("Unknown auth type: $authType")
        };
    }
}
