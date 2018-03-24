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
     * selectuj userow    ok
     * szukaj po imieniu, nazwisko i innych statycznych danych   ok
     * karta usera
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
            'result' => $result
        );
    }

    /**
     * @Route("/search-reader", name="search_reader")
     * @param Request $request
     * @return array
     * @Template("reader/searchReader.html.twig")
     * @Security("has_role('ROLE_USER')")
     */
    public function searchBookAction(Request $request) {

        $search_book = $this->getDoctrine()->getManager()->getRepository('App:Reader')->searchReader($request);

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
