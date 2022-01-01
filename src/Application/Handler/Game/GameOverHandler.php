<?php

namespace Guess\Application\Handler\Game;

use DateTimeImmutable;
use Exception;
use Guess\Domain\Game\Game;
use Guess\Domain\Game\GameRepositoryInterface;
use Guess\Domain\Team\TeamRepositoryInterface;
use RuntimeException;

class GameOverHandler
{
    public function __construct(
        private GameRepositoryInterface $gameRepository,
        private TeamRepositoryInterface $teamRepository
    )
    {
    }

    /**
     * @param array $gameFromApi
     * @throws Exception
     */
    public function handle(array $gameFromApi): void
    {
        if (!isset($gameFromApi['score'])) {
            throw new RuntimeException('Need score to finish the game');
        }

        $homeTeam = $this->teamRepository->findOneBy(['name' => $gameFromApi['homeTeam']]);
        $awayTeam = $this->teamRepository->findOneBy(['name' => $gameFromApi['awayTeam']]);

        if (!$homeTeam) {
            throw new RuntimeException($gameFromApi['homeTeam']. ' is not the part of our database');
        }

        if (!$awayTeam) {
            throw new RuntimeException($gameFromApi['awayTeam']. ' is not the part of our database');
        }

        /** @var Game $game */
        $game = $this->gameRepository->findOneBy(
            [
                'homeTeam' => $homeTeam,
                'awayTeam' => $awayTeam,
                'gameTime' => new DateTimeImmutable($gameFromApi['gameTime'])
            ]
        );

        if (!$game) {
            throw new RuntimeException('Game between ' . $homeTeam->getName() . ' - ' . $awayTeam->getName() . ' is not stored');
        }

        $game->completed($gameFromApi['score']);

        $this->gameRepository->save($game);
    }
}
