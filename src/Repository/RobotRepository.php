<?php

namespace App\Repository;

use App\Entity\Robot;
use App\Enum\EntityEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Robot>
 *
 * @method Robot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Robot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Robot[]    findAll()
 * @method Robot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RobotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Robot::class);
    }

    public function add(Robot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Robot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllActive()
    {
        return $this->createQueryBuilder('r')
            ->where("r.state = :active")
            ->setParameter('active', EntityEnum::STATE_ACTIVE)
            ->getQuery()
            ->getResult();
    }

    public function findWinner(array $ids): Robot
    {
        return $this->createQueryBuilder('r')
            ->where('r.id IN(:ids)')
            ->andWhere("r.state = :active")
            ->setParameter('ids', array_values($ids))
            ->setParameter('active', EntityEnum::STATE_ACTIVE)
            ->orderBy('r.power', 'DESC')
            ->addOrderBy('r.created_at', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
