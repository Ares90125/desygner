<?php

namespace App\Repository;

use App\Entity\LibraryImage;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LibraryImage>
 *
 * @method LibraryImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method LibraryImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method LibraryImage[]    findAll()
 * @method LibraryImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibraryImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LibraryImage::class);
    }

    public function getLibraryImages(User $user)
    {
        $libraryImages = $this->createQueryBuilder('lm')
            ->andWhere('lm.user = :user')
            ->setParameter('user', $user)
            ->innerJoin('lm.image', 'm')
            ->select('lm, m')
            ->getQuery()
            ->getResult();
        return \array_map(function($item) { return $item->getImage(); }, $libraryImages);
    }

    public function add(LibraryImage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LibraryImage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return LibraryImage[] Returns an array of LibraryImage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LibraryImage
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
