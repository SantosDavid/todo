<?php

namespace AppBundle\Repository\Common;

trait Persist
{
    public function persist($class)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($class);
        $entityManager->flush();
    }
}