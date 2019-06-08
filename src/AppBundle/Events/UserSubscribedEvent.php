<?php

namespace AppBundle\Events;

use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserSubscribedEvent extends Event
{
    const NAME = 'user.subscribed';
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}