<?php

namespace Guess\Application\Handler\League;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Guess\Application\Services\FileUploaderInterface;
use Guess\Domain\League\League;
use Guess\Domain\League\LeagueRepositoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class CreateLeagueHandler
{
    private string $bucketName;

    /**
     * @param LeagueRepositoryInterface $leagueRepository
     * @param FileUploaderInterface     $logoUploader
     * @param ContainerBagInterface     $containerBag
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        private LeagueRepositoryInterface $leagueRepository,
        private FileUploaderInterface $logoUploader,
        ContainerBagInterface $containerBag
    ) {
        $this->bucketName = $containerBag->get('AWS_BUCKET_NAME');
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function handle(array $data): void
    {
        if ($this->leagueRepository->findOneBy(['name' => $data['name']])) {
            throw new RuntimeException('League already saved!');
        }

        if (!isset($data['logo'])) {
            throw new RuntimeException('Team logo must be provided!');
        }

        try {
            $this->logoUploader->upload(
                $this->bucketName,
                $data['name'],
                $data['logo']
            );
        } catch (Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }

        $this->leagueRepository->save(
            (new League())
                ->setName($data['name'])
                ->setLogo($this->logoUploader->getImageUrl())
                ->setLeagueApiId($data['leagueApiId'])
                ->setLeagueNameSlugged($data['leagueNameSlugged'])
        );
    }
}
