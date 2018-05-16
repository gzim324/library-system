<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Category;
use App\Form\BookType;
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

        $undeleted_category = $this->getDoctrine()->getManager()->getRepository(Category::class)->undeletedCategory();

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $undeleted_books,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return array(
            'undeletedBooks' => $result,
            'category' => $undeleted_category
        );
    }

    /**
     * @Route("/category/{id}", name="category_book")
     * @Template("book/bookCategory.html.twig")
     * @Security("has_role('ROLE_USER')")
     * @param Category $category
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function categoryBookAction(Category $category, Request $request)
    {
        $undeleted_category = $this->getDoctrine()->getManager()->getRepository(Category::class)->undeletedCategory();

        $books = $this->getDoctrine()->getManager()->getRepository(Book::class)->categoryBook($category);

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $books,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return array(
            'category' => $undeleted_category,
            'result' => $result
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

        $formBook = $this->createForm(BookType::class, $book);

        $formBook->handleRequest($request);

        if($request->isMethod('POST')) {
            if($formBook->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $book->upload();
                $book->setDeleted(false);
                $entityManager->persist($book);
                $entityManager->flush();

                return $this->redirectToRoute("book_index");
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
        if($book->getDeleted() == true) {
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
        $formBook->handleRequest($request);

        if($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();
            $book->upload();
            $entityManager->persist($book);
            $entityManager->flush();

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
        $book->setDeleted(true);
        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute("details_book", ["id" => $book->getId()]);
    }

    /**
     * @Route("/search-book", name="search_book")
     * @param Request $request
     * @return array
     * @Template("book/searchBook.html.twig")
     * @Security("has_role('ROLE_USER')")
     */
    public function searchBookAction(Request $request)
    {

        $search_book = $this->getDoctrine()->getManager()->getRepository(Book::class)->searchBook($request);

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
