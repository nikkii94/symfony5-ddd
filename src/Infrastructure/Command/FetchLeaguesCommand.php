<?php

namespace Guess\Infrastructure\Command;

use Exception;
use Guess\Application\Handler\League\CreateLeagueHandler;
use Guess\Infrastructure\Services\FetchLeaguesInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchLeaguesCommand extends Command
{
    protected static $defaultName = 'app:fetch-leagues';

    public function __construct(
        private CreateLeagueHandler $createLeagueHandler,
        private FetchLeaguesInterface $fetchLeaguesService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $leagues = $this->fetchLeaguesService->fetch([]);

        foreach ($leagues as $league) {
            try {
                $this->createLeagueHandler->handle($league);
                $output->writeln($league['name'] . ' saved');
            } catch (Exception $exception) {
                $output->writeln($exception->getMessage());
            }
        }

        $output->writeln('Leagues are created');

        return Command::SUCCESS;
    }
}
