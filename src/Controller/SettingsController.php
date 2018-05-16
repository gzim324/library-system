<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SettingsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SettingsController extends Controller
{
    /**
     * @Route("/settings/{id}", name="user_settings")
     * @param User $user
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Template()
     * @Security("has_role('ROLE_USER')")
     */
    public function settingsAction(Request $request, User $user)
    {
        if($this->getUser() != $user->getUsername()) {
            throw new AccessDeniedException();
        }

        $formSettings = $this->createForm(SettingsType::class, $user);

        if($request->isMethod('POST')) {
            $formSettings->handleRequest($request);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'The information has been added');

            return $this->redirectToRoute("message_index");
        }

        return array(
            'formSettings' => $formSettings->createView()
        );
    }
}
