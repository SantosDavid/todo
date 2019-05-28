<?php

namespace AppBundle\Filter;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\User\UserInterface;

class Configurator
{
    /**
     * @var ObjectManager
     */
    private $em;
    /**
     * @var TokenStorage
     */
    private $tokenStorage;
    /**
     * @var Reader
     */
    private $reader;
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(ObjectManager $em, TokenStorage $tokenStorage, Reader $reader, KernelInterface $kernel)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->reader = $reader;
        $this->kernel = $kernel;
    }

    public function onKernelRequest()
    {
        $user = $this->getUser();

        if ($user && $this->kernel->getEnvironment() !== 'test') {
            $filter = $this->em->getFilters()->enable('user_filter');
            $filter->setParameter('id', $user->getId());
            $filter->setAnnotationReader($this->reader);
        }
    }

    private function getUser()
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return null;
        }

        $user = $token->getUser();

        if (!($user instanceof UserInterface)) {
            return null;
        }

        return $user;
    }
}