<?php

namespace Guess\Application\Handler\Player;

use Exception;
use Guess\Domain\Game\GameRepositoryInterface;
use Guess\Domain\Player\Player;
use Guess\Domain\Player\PlayerRepositoryInterface;
use RuntimeException;

class MakeAGuessHandler
{
    private PlayerRepositoryInterface $playerRepository;
    private GameRepositoryInterface $gameRepository;

    public function __construct(
        PlayerRepositoryInterface $playerRepository,
        GameRepositoryInterface $gameRepository
    )
    {
        $this->playerRepository = $playerRepository;
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param array $guessArray
     * @throws Exception
     */
    public function handle(array $guessArray): void
    {
        $player = $this->playerRepository->findOneBy(
            ['username' => $guessArray['username']]
        );

        if (!$game = $this->gameRepository->findOneById(
            $guessArray['gameId']
        )) {
            throw new RuntimeException("Game is not there");
        }

        [$homeTeamGuess, $awayTeamGuess] = explode('-', $guessArray['guess']);

        /** @var Player $player */
        $player->makeGuesses($game, $homeTeamGuess, $awayTeamGuess);

        $this->playerRepository->update(
            $player
        );

    }
}
