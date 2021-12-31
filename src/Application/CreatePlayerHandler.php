<?php

namespace Guess\Application;

use Exception;
use Guess\Domain\Player\Player;
use Guess\Domain\Player\PlayerRepositoryInterface;
use RuntimeException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreatePlayerHandler
{
    private PlayerRepositoryInterface $playerRepository;
    private UserPasswordHasherInterface $hasher;

    public function __construct(PlayerRepositoryInterface $playerRepository, UserPasswordHasherInterface $hasher)
    {
        $this->playerRepository = $playerRepository;
        $this->hasher = $hasher;
    }

    /**
     * @param array $playerData
     * @return Player
     * @throws RuntimeException
     */
    public function handle(array $playerData): void
    {
        $player = new Player();
        $player->setUsername($playerData['username']);
        $player->setEmail($playerData['email']);
        $player->setAvatar($playerData['avatar']);
        $player->setPassword($this->hasher->hashPassword($player, $playerData['password']));

        try {
            $this->playerRepository->save($player);
        } catch (Exception $exception) {
            throw new RuntimeException('User can not be saved');
        }
    }
}
