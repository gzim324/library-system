<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Unit;
use App\Form\UnitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UnitController extends Controller
{
    /**
     * @Route("/unit/book/{id}", name="unit_book")
     * @Template("unit/unitBook.html.twig")
     * @param Book $book
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function unitBookAction(Book $book, Request $request, $id)
    {
        $unit = new Unit();
        $formUnit = $this->createForm(UnitType::class, $unit);
        if($request->isMethod('POST')) {
            $formUnit->handleRequest($request);
            $entityManager = $this->getDoctrine()->getManager();
            $unit->setBorrow(false);
            $unit->setDeleted(false);
            $unit->setBook($this->getDoctrine()->getRepository(Book::class)->find($id));
            $entityManager->persist($unit);
            $entityManager->flush();

            return $this->redirectToRoute("unit_book", ['id' => $book->getId()]);
        }
        $selectUnit = $this->getDoctrine()->getManager()->getRepository(Unit::class)->selectUnits($book);

        return array(
            'formUnit' => isset($formUnit) ? $formUnit->createView() : NULL,
            'book' => $book,
            'selectUnit' => $selectUnit
        );
    }

    /**
     * @Route("/delete-unit-book/{id}", name="delete_unit")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @param Unit $unit
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteUnitAction(Unit $unit)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $unit->setDeleted(true);
        $entityManager->persist($unit);
        $entityManager->flush();

        return $this->redirectToRoute("unit_book", ['id' => $unit->getBook()]);
    }

    /**
     * @Route("/unit-book/{id}", name="unit_book_user")
     * @Template("unit/unitBookUser.html.twig")
     * @param Book $book
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_USER')")
     */
    public function unitBookUserAction(Book $book)
    {
        $selectUnit = $this->getDoctrine()->getManager()->getRepository(Unit::class)->selectUnits($book);

        return array(
            'selectUnit' => $selectUnit,
            'book' => $book
        );
    }

    /**
     * @Route("/give-book/{id}", name="give_unit")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @param Unit $unit
     * @Security("has_role('ROLE_USER')")
     */
    public function giveUnitAction(Unit $unit)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $unit->setBorrow(false);
        $unit->setReader(null);
        $unit->setDeadline(null);
        $entityManager->persist($unit);
        $entityManager->flush();

        return $this->redirectToRoute("unit_book", ['id' => $unit->getBook()]);
    }

    /**
     * @Route("/borrow-book/{id}", name="borrow_unit")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @param Unit $unit
     * @Security("has_role('ROLE_USER')")
     */
    public function borrowUnitAction(Unit $unit)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($unit);
        $entityManager->flush();

        return $this->redirectToRoute("unit_book", ['id' => $unit->getBook()]);
    }

}
