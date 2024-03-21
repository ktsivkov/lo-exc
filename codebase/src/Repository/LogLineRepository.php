<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\LogLine;
use App\Module\Log\Dto\SearchCriteriaDto;
use App\Module\Log\Repository\LogLineRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LogLine>
 *
 * @method LogLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogLine[]    findAll()
 * @method LogLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogLineRepository extends ServiceEntityRepository implements LogLineRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogLine::class);
    }

    /**
     * {@inheritDoc}
     */
    public function saveLines(array $entities): void
    {
        $em = $this->getEntityManager();
        foreach ($entities as $entity) {
            $em->persist($entity);
        }
        $em->flush();
    }

    public function getLinesCountByCriteria(SearchCriteriaDto $criteriaDto): int
    {
        $qb = $this->createQueryBuilder('f');
        $qb->select('count(f.id)');
        if ($criteriaDto->serviceNames) {
            $qb->andWhere('f.serviceName IN (:serviceNames)')
                ->setParameter(':serviceNames', $criteriaDto->serviceNames, ArrayParameterType::STRING);
        }
        if ($criteriaDto->statusCode) {
            $qb->andWhere('f.statusCode = :statusCode')
                ->setParameter(':statusCode', $criteriaDto->statusCode);
        }
        if ($criteriaDto->startDate) {
            $qb->andWhere('f.date >= :startDate')
                ->setParameter(':startDate', $criteriaDto->startDate, Types::DATETIME_MUTABLE);
        }
        if ($criteriaDto->endDate) {
            $qb->andWhere('f.date <= :endDate')
                ->setParameter(':endDate', $criteriaDto->endDate, Types::DATETIME_MUTABLE);

        }
        return (int)$qb
            ->getQuery()
            ->getSingleScalarResult();
    }
}
