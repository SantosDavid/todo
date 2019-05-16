<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response
     */
    public function editAction(UserInterface $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $editForm = $this->createForm(UserUpdateType::class, $user);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $password =  $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->getDoctrine()
                ->getRepository(User::class)
                ->update();
        }

        return $this->render(
            'me/edit.html.twig',
            [
                'user' => $user,
                'editForm' => $editForm->createView()
            ]
        );
    }
}
