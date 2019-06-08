<?php

namespace AppBundle\EventListener;

use AppBundle\Events\UserSubscribedEvent;
use Swift_Message;
use Swift_Mailer;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SendEmailAdminListener
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    public function __construct(ContainerInterface $container, Swift_Mailer $mailer)
    {
        $this->container = $container;
        $this->mailer = $mailer;
    }

    public function onUserSubscribed(UserSubscribedEvent $event)
    {
        $user = $event->getUser();

        $message = (new Swift_Message('New user subscribed'))
            ->setFrom($this->container->getParameter('email_from'))
            ->setTo('admin@admin.com.br')
            ->setBody(
                $this->container->get('twig')->render('emails/users/subscribed.html.twig', ['user' => $user]),
               'text/html'
            );

        $this->mailer->send($message);
    }
}