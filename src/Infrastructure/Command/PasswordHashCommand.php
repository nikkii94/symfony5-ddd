<?php

namespace Guess\Infrastructure\Command;

use Guess\Domain\Player\Player;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHashCommand extends Command
{
    protected static $defaultName = 'app:hash-password';

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // configure an argument
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'Password to generate hash'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln($this->hasher->hashPassword(
            new Player(),
            $input->getArgument('password')
        ));

        return Command::SUCCESS;
    }
}
