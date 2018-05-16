<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Route("/category", name="index_category")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $category = new Category();

        $formCategory = $this->createForm(CategoryType::class, $category);

        $formCategory->handleRequest($request);

        if($request->isMethod('POST')) {
            if($formCategory->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $category->setDeleted(0);
                $entityManager->persist($category);
                $entityManager->flush();

                $formCategory->getData();

                return $this->redirectToRoute('index_category');
            }
        }

        $resultFormCategory = $this->getDoctrine()->getRepository(Category::class)->undeletedCategory();

        return array(
            'formCategory' => isset($formCategory) ? $formCategory->createView() : NULL,
            'resultFormCategory' => $resultFormCategory
        );
    }

    /**
     * @Route("/edit-category/{id}", name="edit_category")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction(Category $category, Request $request)
    {
        $formCategory = $this->createForm(CategoryType::class, $category);

        if($request->isMethod('POST')) {
            $formCategory->handleRequest($request);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('index_category');
        }

        return array(
            'formCategory' => $formCategory->createView()
        );
    }

    /**
     * @Route("/remove-category/{id}", name="remove_category")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Category $category)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category->setDeleted(true);
        $entityManager->persist($category);
        $entityManager->flush();

        return $this->redirectToRoute('index_category');
    }
}
