<?php

namespace AppBundle\Entity;

trait Fill
{
    public function fromArray(array $data)
    {
        foreach ($data as $property => $value) {
            $setMethod = "set" . ucfirst($property);

            if (method_exists($this, $setMethod)) {
                $this->{$setMethod}($value);
            }
        }
    }
}