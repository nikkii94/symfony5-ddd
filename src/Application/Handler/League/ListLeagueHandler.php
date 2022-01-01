<?php

namespace Guess\Application\Handler\League;

use Guess\Domain\League\LeagueRepositoryInterface;

class ListLeagueHandler
{
    public function __construct(private LeagueRepositoryInterface $leagueRepository)
    {}

    /**
     * @return array
     */
    public function handle(): array
    {
        return $this->leagueRepository->findAll();
    }
}
