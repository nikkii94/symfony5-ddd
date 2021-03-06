<?php

namespace Guess\Infrastructure\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Guess\Domain\League\League;
use Guess\Domain\League\LeagueRepositoryInterface;

class LeagueRepository extends ServiceEntityRepository implements LeagueRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, League::class);
    }

    public function all(): array
    {
        return $this
            ->createQueryBuilder('l')
            ->orderBy('l.id')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param League $league
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(League $league): void
    {
        $this->_em->persist($league);
        $this->_em->flush();
    }
}
