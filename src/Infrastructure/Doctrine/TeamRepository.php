<?php

namespace Guess\Infrastructure\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Guess\Domain\Team\Team;
use Guess\Domain\Team\TeamRepositoryInterface;

class TeamRepository extends ServiceEntityRepository implements TeamRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function all(): array
    {
        return $this
            ->createQueryBuilder('t')
            ->orderBy('t.id')
            ->getQuery()
            ->getArrayResult();
    }

    public function deleteAll(): void
    {
        $this->createQueryBuilder('t')->delete();
    }

    /**
     * @param Team $team
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(Team $team): void
    {
        $this->_em->persist($team);
        $this->_em->flush();
    }
}
