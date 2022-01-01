<?php

namespace Guess\Infrastructure\Services;

use Symfony\Component\String\Slugger\AsciiSlugger;

class FetchLeagues implements FetchLeaguesInterface
{
    public function __construct(
        private ProviderInterface $provider
    ) {}

    public function fetch(array $input): array
    {
        $leagues = $this->provider->getContent($input);

        $leagueArr = [];

        $slugger = new AsciiSlugger();

        foreach ($leagues['api']['leagues'] as $league) {
            if (
                !in_array(
                    strtolower($slugger->slug($league['name'])->toString()),
                    [
                        'premier-league',
                        'serie-a',
                        'primera-division',
                        'primeira-liga',
                        'super-liga',
                        'uefa-europa-league',
                        'uefa-champions-league',
                        'bundesliga-1',
                        'ligue-1'
                    ],
                    true
                )
            ) {
                continue;
            }

            if (!in_array($league['country'], [
                'England',
                'Italy',
                'France',
                'Portugal',
                'Spain',
                'Turkey',
                'World',
                'Germany'
            ])) {
                continue;
            }

            $leagueArr[] = [
                'leagueApiId' => $league['league_id'],
                'name' => $league['name'],
                'logo' => $league['logo'],
                'leagueNameSlugged' => strtolower($slugger->slug($league['name'])->toString())
            ];
        }

        return $leagueArr;
    }
}
