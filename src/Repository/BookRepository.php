<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @return array
     */
    public function undeletedBooks() {
        return $this->createQueryBuilder("book")
            ->where("book.deleted = :false")
            ->setParameter("false", 0)
            ->orderBy("book.id", "DESC")
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function searchBook(Request $request) {
        return $this->createQueryBuilder('book')
            ->where('book.title LIKE :search')
            ->orWhere('book.author LIKE :search')
            ->orWhere('book.isbn LIKE :search')
            ->setParameter('search', '%'.$request->get('searchBook').'%')
            ->andWhere('book.deleted = :false')
            ->setParameter('false', 0)
            ->getQuery()
            ->getResult();
    }
}
