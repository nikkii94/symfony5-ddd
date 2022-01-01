<?php

namespace Guess\Application\Handler\Team;

use Exception;
use Guess\Application\Services\FileUploaderInterface;
use Guess\Domain\Team\Team;
use Guess\Domain\Team\TeamRepositoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class CreateTeamHandler
{
    private TeamRepositoryInterface $teamRepository;
    private FileUploaderInterface $logoUploader;
    private string $bucketName;

    /**
     * @param TeamRepositoryInterface $teamRepository
     * @param FileUploaderInterface   $logoUploader
     * @param ContainerBagInterface   $containerBag
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        TeamRepositoryInterface $teamRepository,
        FileUploaderInterface $logoUploader,
        ContainerBagInterface $containerBag
    ) {
        $this->teamRepository = $teamRepository;
        $this->logoUploader = $logoUploader;
        $this->bucketName = $containerBag->get('AWS_BUCKET_NAME');
    }

    /**
     * @param array $team
     * @throws Exception
     */
    public function handle(array $team): void
    {
        if (!isset($team['name'])) {
            return;
        }

        $team = $this->teamRepository->findOneBy(
            [
                'name' => $team['name']
            ]
        );

        if ($team instanceof Team) {
            throw new RuntimeException('Team already saved');
        }

        if (!isset($team['logo'])) {
            throw new RuntimeException('We need team logo to save the team');
        }

        try {
            $this->logoUploader->upload($this->bucketName, $team['name'], $team['logo']);
        } catch (Exception $exception) {
            throw new RuntimeException("Cant upload the logo: " . $exception);
        }

        $this->teamRepository->save(
            (new Team())
                ->setName($team['name'])
                ->setLogo($this->logoUploader->getImageUrl())
        );
    }
}
