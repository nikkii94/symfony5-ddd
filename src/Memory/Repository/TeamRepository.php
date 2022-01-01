<?php

namespace Guess\Memory\Repository;

use Guess\Domain\Team\Team;
use Guess\Domain\Team\TeamRepositoryInterface;

class TeamRepository implements TeamRepositoryInterface
{
    private array $teams = [];

    public function findOneBy(array $criteria): ?Team
    {
        /** @var Team $team */
        foreach ($this->teams as $team) {
            if ($team->getName() === $criteria['name']) {
                return $team;
            }
        }

        return null;
    }

    public function getAll(): array
    {
        return $this->teams;
    }

    public function save(Team $team): void
    {
        $this->teams[] = $team;
    }
}
