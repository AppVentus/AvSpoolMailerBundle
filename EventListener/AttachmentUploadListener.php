<?php

namespace AppVentus\Awesome\SpoolMailerBundle\EventListener;

use AppVentus\Awesome\SpoolMailerBundle\Entity\Attachment;
use AppVentus\Awesome\SpoolMailerBundle\Utils\AttachmentUploader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Class AttachmentUploadListener
 *
 * @package AppVentus\Awesome\SpoolMailerBundle\EventListener
 */
class AttachmentUploadListener
{
    private $uploader;

    /**
     * AttachmentUploadListener constructor.
     *
     * @param \AppVentus\Awesome\SpoolMailerBundle\Utils\AttachmentUploader $uploader
     */
    public function __construct(AttachmentUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity, $args->getEntityManager());
    }

    /**
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity, $args->getEntityManager());
    }

    /**
     * @param $entity
     * @param EntityManager $em
     */
    private function uploadFile($entity, EntityManager $em)
    {
        if (!$entity instanceof Attachment) {
            return;
        }

        $this->uploader->upload($entity, $em);
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Attachment)
        {
            $this->uploader->remove($entity->getFileName());
        }
    }
}