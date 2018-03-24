<?php

namespace App\Controller;

use App\Entity\Reader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReaderController extends Controller
{
    /*
     * dodaj usera
     * selectuj userow
     * szukaj po imieniu, nazwisko i innych statycznych danych
     */


    /**
     * @Route("/reader", name="reader_index")
     * @Template("reader/select.html.twig")
     * @return array
     * @param Request $request
     * @Security("has_role('ROLE_USER')")
     */
    public function selectAction(Request $request)
    {
        $readers = $this->getDoctrine()->getManager()->getRepository(Reader::class)->findAll();

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $readers,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 15)
        );

        return array(
            'readers' => $result
        );
    }
}
