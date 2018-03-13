<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Unit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
}
