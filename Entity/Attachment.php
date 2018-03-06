<?php

namespace AppVentus\Awesome\SpoolMailerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * AppVentus\Awesome\SpoolMailerBundle\Entity\attachment.
 *
 * @ORM\Table(name="mail_attachment")
 * @ORM\Entity
 */
class Attachment
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="fileName", type="string")
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(name="client_original_name", type="string")
     */
    private $clientOriginalName;

    /**
     * @var string
     *
     * @ORM\Column(name="path_name", type="string")
     */
    private $pathName;

    /**
     * @var Mail
     *
     * @ORM\ManyToOne(targetEntity="AppVentus\Awesome\SpoolMailerBundle\Entity\Mail", inversedBy="attachments")
     */
    private $mail;

    /**
     * @var \Swift_Attachment
     */
    private $swiftAttachment;

    /**
     * @var string
     *
     * @ORM\Column(name="swift_attachment_id", type="string", nullable=true)
     */
    private $swiftAttachmentId;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getClientOriginalName()
    {
        return $this->clientOriginalName;
    }

    /**
     * @param string $clientOriginalName
     */
    public function setClientOriginalName($clientOriginalName)
    {
        $this->clientOriginalName = $clientOriginalName;
    }

    /**
     * @return string
     */
    public function getPathName()
    {
        return $this->pathName;
    }

    /**
     * @param string $pathName
     */
    public function setPathName($pathName)
    {
        $this->pathName = $pathName;
    }

    /**
     * @return \Swift_Attachment
     */
    public function getSwiftAttachment()
    {
        return $this->swiftAttachment;
    }

    /**
     * @param \Swift_Attachment $swiftAttachment
     */
    public function setSwiftAttachment($swiftAttachment)
    {
        $this->swiftAttachmentId = $swiftAttachment->getId();
        $this->swiftAttachment = $swiftAttachment;
    }

    /**
     * @return \AppVentus\Awesome\SpoolMailerBundle\Entity\Mail
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param \AppVentus\Awesome\SpoolMailerBundle\Entity\Mail $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    /**
     * @return string
     */
    public function getSwiftAttachmentId()
    {
        return $this->swiftAttachmentId;
    }
}
