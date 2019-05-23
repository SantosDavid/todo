<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/me", name="me.")
 */
class MeControllerController extends Controller
{
    /**
     * @Route("/edit", name="edit")
     *
     * @param UserInterface $user
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(UserInterface $user, Request $request)
    {
        $editForm = $this->createForm(UserType::class, $user);

        $editForm->remove('username');

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()
                ->getRepository(User::class)
                ->update();
        }

        return $this->render('me/edit.html.twig', [
            'user' => $user,
            'editForm' => $editForm->createView()
        ]);
    }
}
