<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Unit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Unit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Unit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Unit[]    findAll()
 * @method Unit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Unit::class);
    }


    /**
     * @param Book $book
     * @return mixed
     */
    public function selectUnits(Book $book)
    {
        return $this->createQueryBuilder('unit')
            ->where('unit.book = :book')
            ->setParameter('book', $book->getId())
            ->andWhere('unit.deleted = :status')
            ->setParameter('status', false)
            ->getQuery()
            ->getResult()
        ;
    }

    public function borrowedUnits()
    {
        return $this->createQueryBuilder('unit')
            ->where('unit.borrow = :borrow')
            ->setParameter("borrow", true)
            ->andWhere('unit.deleted = :status')
            ->setParameter('status', false)
            ->getQuery()
            ->getResult()
            ;
    }

    public function borrowedBook(Unit $unit)
    {
        return $this->createQueryBuilder('unit')
            ->where('unit.reader = :reader')
            ->setParameter("reader", $unit->getId())
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function searchBorrowedUnit(Request $request) {
        return $this->createQueryBuilder('unit')
            ->where('unit.unit = :search')
            ->setParameter('search', '%'.$request->get('searchBorrowedUnit').'%')
            ->getQuery()
            ->getResult();
    }
}
