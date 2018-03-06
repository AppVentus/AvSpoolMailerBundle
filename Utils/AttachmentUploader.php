<?php

namespace AppVentus\Awesome\SpoolMailerBundle\Utils;

use AppVentus\Awesome\SpoolMailerBundle\Entity\Attachment;
use Doctrine\ORM\EntityManager;
use Gedmo\Exception\UploadableDirectoryNotFoundException;

/**
 * Class AttachmentUploader.
 */
class AttachmentUploader
{
    private $targetDir;
    private $rootDir;

    /**
     * AttachmentUploader constructor.
     *
     * @param   $targetDir
     * @param   $rootDir
     */
    public function __construct($targetDir, $rootDir)
    {
        $this->targetDir = $targetDir;
        $this->rootDir = $rootDir;
    }

    /**
     * @param \AppVentus\Awesome\SpoolMailerBundle\Entity\Attachment $attachment
     * @param EntityManager                                          $em
     */
    public function upload(Attachment $attachment, EntityManager $em)
    {
        if ($swiftAttachment = $attachment->getSwiftAttachment()) {
            /** @var Attachment $duplicatedAttachment */
            $duplicatedAttachment = $em->getRepository(Attachment::class)->findOneBy(['swiftAttachmentId' => $attachment->getSwiftAttachmentId()]);
            if (!$duplicatedAttachment) {
                $fileName = md5(uniqid('', true)).'_'.$swiftAttachment->getFilename();
                if (!@mkdir($this->targetDir, 0777, true) && !is_dir($this->targetDir)) {
                    throw new UploadableDirectoryNotFoundException(sprintf('Cannot found or create %s directory', $this->targetDir));
                }

                $filePath = $this->rootDir.'/../web/'.$this->targetDir.'/'.$fileName;
                file_put_contents($filePath, $swiftAttachment->getBody());
                $attachment->setFileName(basename($filePath));
                $attachment->setPathName($filePath);
                $attachment->setClientOriginalName($swiftAttachment->getFilename());
            } else {
                $attachment->setFileName($duplicatedAttachment->getFileName());
                $attachment->setPathName($duplicatedAttachment->getPathName());
                $attachment->setClientOriginalName($duplicatedAttachment->getClientOriginalName());
            }
        }
    }

    /**
     * @param $fileName
     */
    public function remove($fileName)
    {
        $filePath = $this->rootDir.'/../web/'.$this->targetDir.'/'.$fileName;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
