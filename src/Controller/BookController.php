<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Unit;
use App\Form\BookType;
use App\Form\UnitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class BookController extends Controller
{
    /**
     * @Route("/book", name="book_index")
     * @Template("book/bookIndex.html.twig")
     * @return array
     * @param Request $request
     * @Security("has_role('ROLE_USER')")
     */
    public function bookIndexAction(Request $request)
    {
        $undeleted_books = $this->getDoctrine()->getManager()->getRepository(Book::class)->undeletedBooks();

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $undeleted_books,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return array(
            'undeletedBooks' => $result
        );
    }

    /**
     * @Route("/new-book", name="new_book")
     * @Template("book/newBook.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newBookAction(Request $request)
    {
        $book = new Book();

//        $category = new Category();
//        $category->setName('AI');

        $formBook = $this->createForm(BookType::class, $book);

        $formBook->handleRequest($request);
        if($request->isMethod('POST')) {
            if($formBook->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $book->upload();
                $book->setDeleted(0);
                $entityManager->persist($book);
                $entityManager->flush();

                $formBook->getData();
//                $this->addFlash("success", "The book has been added");
                return $this->redirectToRoute("details_book", ['id' => $book->getId()]);
            }
        }

        return array(
            'formBook' => isset($formBook) ? $formBook->createView() : NULL
        );
    }

    /**
     * @Route("/details-book/{id}", name="details_book")
     * @Template("book/detailsBook.html.twig")
     * @param Book $book
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_USER')")
     */
    public function detailsBookAction(Book $book)
    {
        if($book->getDeleted() == 1) {
            $this->addFlash("error", "This book does not exist");
            return $this->redirectToRoute("book_index");
        }

        return array(
            'book' => $book
        );
    }

    /**
     * @Route("/update-book/{id}", name="update_book")
     * @Template("book/updateBook.html.twig")
     * @param Book $book
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function updateBookAction(Book $book, Request $request)
    {
        $formBook = $this->createForm(BookType::class, $book);
        if($request->isMethod('POST')) {
            $formBook->handleRequest($request);
            $entityManager = $this->getDoctrine()->getManager();
            $book->upload();
            $entityManager->persist($book);
            $entityManager->flush();

//            $this->addFlash("success", "The book has been updated");

            return $this->redirectToRoute("details_book", ['id' => $book->getId()]);
        }

        return array(
            'formBook' => $formBook->createView()
        );
    }

    /**
     * @Route("/delete-book/{id}", name="delete_book")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @param Book $book
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteBookAction(Book $book)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $book->upload();
        $book->setDeleted(1);
        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute("book_index");
    }

    /**
     * @Route("/search-book", name="search_book")
     * @param Request $request
     * @return array
     * @Template("book/searchBook.html.twig")
     * @Security("has_role('ROLE_USER')")
     */
    public function searchBookAction(Request $request) {

        $search_book = $this->getDoctrine()->getManager()->getRepository('App:Book')->searchBook($request);

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $search_book,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return array(
            'result' => $result
        );
    }
}
