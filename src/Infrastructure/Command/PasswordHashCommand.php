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
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:hash-password';

    private $hasher;

    public function __construct(string $name = null, UserPasswordHasherInterface $hasher)
    {
        parent::__construct($name);

        $this->hasher = $hasher;
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

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}
