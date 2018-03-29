<?php

namespace App\Controller;

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
     * @Route("/give-book/{id}", name="give")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @param Unit $unit
     * @Security("has_role('ROLE_USER')")
     */
    public function giveAction(Unit $unit)
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
        if($request->isMethod('POST')) {
            if($formBorrow->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $unit->setBorrow(true);

                $em->persist($unit);
                $em->flush();

                return $this->redirectToRoute("select_borrowed");
            }
        }

        return array(
            'formBorrow' => isset($formBorrow) ? $formBorrow->createView() : NULL,
            'unit' => $unit
        );
    }
}
