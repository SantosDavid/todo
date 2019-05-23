<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use AppBundle\Service\General\FileUploaderService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserPhotoUpdateListener
{
    /**
     * @var FileUploaderService
     */
    private $fileUploaderService;

    public function __construct(FileUploaderService $fileUploaderService)
    {
        $this->fileUploaderService = $fileUploaderService;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $fieldsUpdating = array_keys($args->getEntityChangeSet());

        if (!in_array('photo', $fieldsUpdating)) {
            return;
        }

        $newPhoto = $args->getEntityChangeSet()['photo'][1];

        $entity = $args->getEntity();

        if (is_null($newPhoto)) {
            $entity->setPhoto($args->getEntityChangeSet()['photo'][0]);
        }

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        if (!$entity instanceof User) {
            return;
        }

        $photo = $entity->getPhoto();

        if ($photo instanceof UploadedFile) {
            $fileName = $this->fileUploaderService->upload($photo);
            return $entity->setPhoto($fileName);
        }

        if ($photo instanceof File) {
            return $entity->setPhoto($photo->getFilename());
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof User) {
            return;
        }

        if ($fileName = $entity->getPhoto()) {
            $entity->setPhoto(new File($this->fileUploaderService->getTargetDirectory().'/'.$fileName));
        }
    }
}