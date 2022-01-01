<?php

namespace Guess\Infrastructure\RapidApi;

use DateTimeImmutable;
use Exception;
use Guess\Infrastructure\Services\ProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Utils;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class RapidApiClient implements ProviderInterface
{
    public const API_FOOTBALL_URI_LEAGUES = '/v2/leagues/season/2020';
    public const API_FOOTBALL_URI_TEAMS = '/v2/teams/league/';
    public const API_FOOTBALL_URI_GAMES = '/v2/fixtures/date/';

    private $host;

    private Client $client;

    /**
     * @param ContainerBagInterface $containerBag
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerBagInterface $containerBag)
    {
        $this->host = $containerBag->get('RAPIDAPI_HOST');
        $this->client = new Client(
            ['headers' =>
                [
                    'X-RapidAPI-Key' =>  $containerBag->get('RAPIDAPI_KEY')
                ]
            ]
        );
    }

    /**
     * @param array $criteria
     * @return array|bool|float|int|object|string|null
     * @throws GuzzleException
     * @throws Exception
     */
    public function getContent(array $criteria): float|object|int|bool|array|string|null
    {
        $response = "";

        if (!$criteria) {
            $response = $this->client->request(
                'GET',
                $this->host . self::API_FOOTBALL_URI_LEAGUES
            );
        }

        if (isset($criteria['league-api-id'])) {
            $response = $this->client->request(
                'GET',
                $this->host . self::API_FOOTBALL_URI_TEAMS.$criteria['league-api-id']
            );
        }

        if (isset($criteria['days'])) {
            $response = $this->client->request(
                'GET',
                $this->host . self::API_FOOTBALL_URI_GAMES.
                (new DateTimeImmutable($criteria['days'] . " day"))
                    ->format('Y-m-d')
            );
        }

        return Utils::jsonDecode(
            $response->getBody()->getContents(), true
        );
    }
}
