<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class EmployeeController extends Controller
{
    /**
     * @Route("/employee", name="employee_index")
     * @Template("employee/employeeIndex.html.twig")
     * @param Request $request
     * @return array
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function employeeIndexAction(Request $request)
    {
        $selectUsers = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $selectUsers,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return array(
            'selectUsers' => $result
        );
    }

    /**
     * @Route("/new-employee", name="new_employee")
     * @Template("employee/newEmployee.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newEmployeeAction(Request $request)
    {
        $user = new User();

        $formUser = $this->createForm(UserType::class, $user);

        $formUser->handleRequest($request);
        if($request->isMethod('POST')) {
            if($formUser->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $user->setEnabled(1);
                $entityManager->persist($user);
                $entityManager->flush();

                $formUser->getData();
                $this->addFlash("success", "The employee has been added");
                return $this->redirectToRoute("employee_index");
            }else {
                $this->addFlash("danger", "The employee cannot be added");
            }
        }

        return array(
            'formUser' => isset($formUser) ? $formUser->createView() : NULL
        );
    }

    /**
     * @Route("/details-employee/{id}", name="details_employee")
     * @Template("employee/detailsEmployee.html.twig")
     * @param User $user
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function detailsEmployeeAction(User $user)
    {
        return array(
            'user' => $user
        );
    }

    /**
     * @Route("/deactivate/employee/{id}", name="deactivate_employee")
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAccountAction($id) {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $user->setEnabled(false);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('danger', "Account has deleted");
        return $this->redirectToRoute('employee_index');
    }

    /**
     * @Route("/activate/employee/{id}", name="activate_employee")
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function undeleteAccountAction($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $user->setEnabled(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('danger', "Account has undeleted");
        return $this->redirectToRoute('employee_index');
    }

    /**
     * @Route("/reset/password/employee/{id}", name="reset_password_employee")
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function resetPasswordEmployeeAction($id) {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $user->setPassword('$2y$13$ERRGpx51PYzpn/N27LIaBu2T0Z3WmUzxEZ/eSB4pggJdvAacYpCDS'); // password = test

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('danger', "Account has undeleted");
        return $this->redirectToRoute('employee_index');
    }
}
