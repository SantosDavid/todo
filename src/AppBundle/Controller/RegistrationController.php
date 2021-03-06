<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Events\UserSubscribedEvent;
use AppBundle\Form\UserType;
use AppBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends Controller
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(EventDispatcherInterface $eventDispatcher, UserRepository $userRepository)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new UserSubscribedEvent($user);
            $this->eventDispatcher->dispatch(UserSubscribedEvent::NAME, $event);

            $this->userRepository->persist($user);

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'registration/register.html.twig',
            ['form' => $form->createView()]
        );
    }
}
