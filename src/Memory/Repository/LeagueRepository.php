<?php

namespace Guess\Memory\Repository;

use Guess\Domain\League\League;
use Guess\Domain\League\LeagueRepositoryInterface;

class LeagueRepository implements LeagueRepositoryInterface
{
    private array $leagues = [];

    /**
     * @param int $id
     * @return League|null
     */
    public function findOneById(int $id): ?League
    {
        foreach ($this->leagues as $league) {
            if ($league->getId() === $id) {
                return $league;
            }
        }

        return null;
    }

    public function findOneBy(array $criteria): ?League
    {
        /** @var League $league */
        foreach ($this->leagues as $league) {
            if (isset($criteria['name']) && $league->getName() === $criteria['name']) {
                return $league;
            }

            if (isset($criteria['leagueApiId']) && $league->getLeagueApiId() === $criteria['leagueApiId']) {
                return $league;
            }

            if (
                isset($criteria['leagueNameSlugged'])
                && $league->getLeagueNameSlugged() === $criteria['leagueNameSlugged']
            ) {
                return $league;
            }
        }

        return null;
    }

    public function save(League $league): void
    {
        $this->leagues[] = $league;
    }

    public function getLeagues(): array
    {
        return $this->leagues;
    }

    public function findAll(): array
    {
        return $this->leagues;
    }
}
