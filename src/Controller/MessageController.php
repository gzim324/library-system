<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends Controller
{
    /**
     * @Route("/", name="message_index")
     * @Template("message/messageIndex.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_USER')")
     */
    public function messageIndexAction(Request $request)
    {
        $Message = new Message();

        $formMessage = $this->createForm(MessageType::class, $Message);

        $formMessage->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($formMessage->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $Message->setDeleted(false);
                $entityManager->persist($Message);
                $entityManager->flush();

                $formMessage->getData();

                return $this->redirect($this->generateUrl('message_index'));
            }
        }

        $resultFormMessage = $this->getDoctrine()->getRepository(Message::class)->undeletedMessages();

        return array(
            'formMessage' => isset($formMessage) ? $formMessage->createView() : null,
            'resultFormMessage' => $resultFormMessage
        );
    }

    /**
     * @Route("/delete/message/{id}", name="delete_message")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteMessageAction($id)
    {
        $message = $this->getDoctrine()->getRepository(Message::class)->find($id);

        if (null == $message) {
            throw $this->createNotFoundException('Not Found entry in this database');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $message->setDeleted(true);
        $entityManager->persist($message);
        $entityManager->flush();

        return $this->redirect($this->generateUrl('message_index'));
    }
}
