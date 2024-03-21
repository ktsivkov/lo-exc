<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\FileReadOffset;
use App\Module\Entity\FileReadOffsetInterface;
use App\Module\File\Repository\FileReadOffsetRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FileReadOffset>
 *
 * @method FileReadOffset|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileReadOffset|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileReadOffset[]    findAll()
 * @method FileReadOffset[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileReadOffsetRepository extends ServiceEntityRepository implements FileReadOffsetRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileReadOffset::class);
    }

    public function findOneByFileLocation(string $fileLocation): ?FileReadOffsetInterface
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.filename = :fileLocation')
            ->setParameter('fileLocation', $fileLocation)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function saveFileOffset(string $fileLocation, int $offset): void
    {
        $em = $this->getEntityManager();
        $entity = $this->findOneByFileLocation($fileLocation);
        if (!$entity) {
            $entity = new FileReadOffset();
            $entity->setFilename($fileLocation);
        }
        $entity->setOffsetPointer($offset);
        $em->persist($entity);
        $em->flush();
    }
}
