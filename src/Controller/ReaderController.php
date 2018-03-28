<?php

namespace App\Controller;

use App\Entity\Reader;
use App\Entity\Unit;
use App\Form\ReaderType;
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
            'result' => $result,
        );
    }

    /**
     * @Route("/new-reader", name="new_reader")
     * @Template("reader/newReader.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_USER')")
     */
    public function newReaderAction(Request $request)
    {
        $reader = new Reader();

        $formReader = $this->createForm(ReaderType::class, $reader);

        $formReader->handleRequest($request);
        if($request->isMethod('POST')) {
            if($formReader->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($reader);
                $entityManager->flush();

                $formReader->getData();
//                $this->addFlash("success", "The book has been added");
                return $this->redirectToRoute("card_reader", ['id' => $reader->getId()]);
            }
        }

        return array(
            'formReader' => isset($formReader) ? $formReader->createView() : NULL
        );
    }

    /**
     * @Route("/card-reader/{id}", name="card_reader")
     * @Template("reader/cardReader.html.twig")
     * @param Reader $reader
     * @param Unit $unit
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_USER')")
     */
    public function cardReaderAction(Reader $reader, Unit $unit)
    {
        $borrowedBook = $this->getDoctrine()->getManager()->getRepository('App:Unit')->borrowedBook($unit);

        return array(
            'reader' => $reader,
            'borrowedBook' => $borrowedBook
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
