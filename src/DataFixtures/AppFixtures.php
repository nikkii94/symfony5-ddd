<?php

namespace Guess\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Guess\Domain\League\League;
use Guess\Domain\Player\Player;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $league = (new League())->setId(1);
        $league->setName('Premier League');
        $league->setLeagueNameSlugged('premier-league');
        $league->setLeagueApiId(123);
        $league->setLogo('premier-league-logo.png');

        $manager->persist($league);

        $player = new Player();
        $player->setUsername('demo');
        $player->setEmail('test@Test.com');
        $player->setPassword($this->passwordHasher->hashPassword($player, '123123'));

        $manager->persist($player);

        $manager->flush();
    }
}
