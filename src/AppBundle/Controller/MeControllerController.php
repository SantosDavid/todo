<?php

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Repository\UserRepository;
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
     * @var UserRepository
     */
    private $userRepository;


    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/edit", name="edit")
     * @param UserInterface $user
     * @param Request $request
     * @return Response
     */
    public function editAction(UserInterface $user, Request $request)
    {
        $editForm = $this->createForm(UserType::class, $user);

        $editForm->remove('username');

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->userRepository->update();
        }

        return $this->render('me/edit.html.twig', [
            'user' => $user,
            'editForm' => $editForm->createView()
        ]);
    }
}
