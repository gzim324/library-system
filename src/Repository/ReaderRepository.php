<?php

namespace App\Repository;

use App\Entity\Reader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Reader|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reader|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reader[]    findAll()
 * @method Reader[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReaderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reader::class);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function searchReader(Request $request) {
        return $this->createQueryBuilder('reader')
            ->where('reader.fullname LIKE :search')
            ->orWhere('reader.email LIKE :search')
            ->orWhere('reader.phone LIKE :search')
            ->orWhere('reader.city LIKE :search')
            ->setParameter('search', '%'.$request->get('searchReader').'%')
            ->getQuery()
            ->getResult();
    }
}
