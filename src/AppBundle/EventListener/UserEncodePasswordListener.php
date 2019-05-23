<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserEncodePasswordListener
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->encodePassword($args->getEntity());
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $fieldsUpdating = array_keys($args->getEntityChangeSet());

        if (in_array('password', $fieldsUpdating)) {
            $this->encodePassword($args->getEntity());
        }
    }

    private function encodePassword($entity)
    {
        if (!$entity instanceof User) {
            return;
        }

        $password =  $this->passwordEncoder->encodePassword($entity, $entity->getPassword());
        $entity->setPassword($password);
    }
}