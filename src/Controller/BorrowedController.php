<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BorrowedController extends Controller
{
    /**
     * @Route("/borrowed", name="borrowed")
     */
    public function index()
    {
        return $this->render('borrowed/index.html.twig', [
            'controller_name' => 'BorrowedController',
        ]);
    }
}
