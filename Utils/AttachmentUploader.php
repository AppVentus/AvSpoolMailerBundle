<?php
namespace AppVentus\Awesome\SpoolMailerBundle\Utils;

use AppVentus\Awesome\SpoolMailerBundle\Entity\Attachment;
use Doctrine\Bundle\DoctrineBundle\Registry as DoctrineRegistry;
use Gedmo\Exception\UploadableDirectoryNotFoundException;
use Swift_FileStream;

/**
 * Class AttachmentUploader
 *
 * @package AppVentus\Awesome\SpoolMailerBundle\Utils
 */
class AttachmentUploader {

    private $targetDir;
    private $rootDir;
    private $em;

    /**
     * AttachmentUploader constructor.
     *
     * @param                                          $targetDir
     * @param                                          $rootDir
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $registry
     */
    public function __construct($targetDir, $rootDir, DoctrineRegistry $registry)
    {
        $this->targetDir = $targetDir;
        $this->rootDir = $rootDir;
        $this->em = $registry->getManager();
    }

    /**
     * @param \AppVentus\Awesome\SpoolMailerBundle\Entity\Attachment $attachment
     */
    public function upload(Attachment $attachment)
    {
        if ($swiftAttachment = $attachment->getSwiftAttachment())
        {
            /** @var Attachment $duplicatedAttachment */
            $duplicatedAttachment = $this->em->getRepository(Attachment::class)->findOneBy(['swiftAttachmentId' => $attachment->getSwiftAttachmentId()]);
            if (!$duplicatedAttachment)
            {
                $fileName = md5(uniqid('', true)).'_'.$swiftAttachment->getFilename();
                if (!@mkdir($this->targetDir, 0777, true) && !is_dir($this->targetDir)) {
                    throw new UploadableDirectoryNotFoundException(sprintf('Cannot found or create %s directory', $this->targetDir));
                }

                $filePath = $this->rootDir . '/../web/' . $this->targetDir . '/' . $fileName;
                file_put_contents($filePath, $swiftAttachment->getBody());
                $attachment->setFileName(basename($filePath));
                $attachment->setPathName($filePath);
                $attachment->setClientOriginalName($swiftAttachment->getFilename());
            }else {
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
        $filePath = $this->rootDir . '/../web/' . $this->targetDir . '/' . $fileName;
        if(file_exists($filePath))
        {
            unlink($filePath);
        }
    }
}