<?php

namespace App\Controller;

use App\Entity\Reader;
use App\Entity\Unit;
use App\Form\BorrowType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BorrowedController extends Controller
{
    /**
     * @Route("/borrowed", name="select_borrowed")
     * @Template("borrowed/select.html.twig")
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_USER')")
     */
    public function selectAction()
    {
        $selectBorrowedUnits = $this->getDoctrine()->getManager()->getRepository(Unit::class)->borrowedUnits();

        return array(
            'selectBorrowedUnits' => $selectBorrowedUnits,
        );
    }

    /**
     * @Route("/borrowed/{id}", name="details_borrowed")
     * @Template("borrowed/details.html.twig")
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_USER')")
     * @param Unit $unit
     */
    public function detailsAction(Unit $unit)
    {
        return array(
            'unit' => $unit
        );
    }

    /**
     * @Route("/give-book/{id}/{reader}", name="give")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @param Unit $unit
     * @param Reader $reader
     * @Security("has_role('ROLE_USER')")
     */
    public function giveAction(Unit $unit, Reader $reader)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reader->removeUnit($unit);
        $entityManager->persist($reader);
        $entityManager->flush();

        return $this->redirectToRoute("unit_book_user", ['id' => $unit->getBook()->getId()]);
    }

    /**
     * @Route("/borrow-book/{id}", name="borrow-book")
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Template("borrowed/borrow.html.twig")
     * @param Unit $unit
     * @param Request $request
     * @Security("has_role('ROLE_USER')")
     */
    public function borrowAction(Unit $unit, Request $request)
    {
        $formBorrow = $this->createForm(BorrowType::class, $unit);

        $formBorrow->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($formBorrow->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $unit->setBorrow(true);

                $em->persist($unit);
                $em->flush();

                return $this->redirectToRoute('details_borrowed', ['id' => $unit->getId()]);
            }
        }

        return array(
            'formBorrow' => isset($formBorrow) ? $formBorrow->createView() : null,
            'unit' => $unit
        );
    }

    /**
     * @Route("/search-unit", name="searchBorrowedUnit")
     * @param Request $request
     * @return array
     * @Template("borrowed/searchBorrowedUnit.html.twig")
     * @Security("has_role('ROLE_USER')")
     */
    public function searchBorrowedUnitAction(Request $request)
    {
        $search_borrowed_unit = $this->getDoctrine()->getManager()->getRepository(Unit::class)->searchBorrowedUnit($request);

        return array(
            'result' => $search_borrowed_unit
        );
    }
}
