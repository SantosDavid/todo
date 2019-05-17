<?php


namespace AppBundle\Repository\Filter;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class UserFilter extends SQLFilter
{
    protected $reader;

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (empty($this->reader)) {
            return '';
        }

        $userAware = $this->reader->getClassAnnotation(
            $targetEntity->getReflectionClass(),
            'AppBundle\\Annotation\\UserAware'
        );

        if (!$userAware) {
            return '';
        }

        $fieldName = $userAware->userFieldName;

        $userId = $this->getParameter('id');



        return "{$targetTableAlias}.{$fieldName}={$userId}";
    }

    public function setAnnotationReader(Reader $reader)
    {
        $this->reader = $reader;
    }
}