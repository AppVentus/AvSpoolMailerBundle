<?php

namespace AppVentus\Awesome\SpoolMailerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Swift_Mime_SimpleHeaderSet as HeaderSet;
use WhiteOctober\SwiftMailerDBBundle\EmailInterface;

/**
 * AppVentus\Awesome\SpoolMailerBundle\Entity\Mail.
 *
 * @ORM\Table(name="mail")
 * @ORM\Entity
 */
class Mail implements EmailInterface
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
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="mail_body", type="text")
     */
    private $body;

    /**
     * @var array
     *
     * @ORM\Column(name="sender", type="array", nullable=true)
     */
    private $from;

    /**
     * @var array
     *
     * @ORM\Column(name="recipient", type="array", nullable=true)
     */
    private $to;
    /**
     * @var array
     *
     * @ORM\Column(name="cc", type="array", nullable=true)
     */
    private $cc;
    /**
     * @var array
     *
     * @ORM\Column(name="bcc", type="array", nullable=true)
     */
    private $bcc;

    /**
     * @var array
     *
     * @ORM\Column(name="reply_to", type="array", length=255, nullable=true)
     */
    private $replyTo;

    /**
     * @var Attachment []
     *
     * @ORM\OneToMany(targetEntity="AppVentus\Awesome\SpoolMailerBundle\Entity\Attachment", mappedBy="mail", cascade={"persist", "remove"})
     */
    private $attachments;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="text", nullable=true)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="content_type", type="text", nullable=true)
     */
    private $contentType;

    /**
     * @var array
     *
     * @ORM\Column(name="headers", type="array", nullable=true)
     */
    private $headers;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="send_date", type="datetime", nullable=true)
     */
    protected $sendDate;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    protected $type;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    /**
     * Set sendDate.
     *
     * @param string $sendDate
     *
     * @return Event
     */
    public function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;
    }

    /**
     * Get start_date.
     *
     * @return string
     */
    public function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Event
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get start_date.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set message.
     *
     * @param string $message The message to send
     *
     * @return Mail
     */
    public function setMessage($message)
    {
        $this->setSubject($message->getSubject());
        $this->setBody($message->getBody());
        $this->setTo($message->getTo());
        $this->setCc($message->getCc());
        $this->setBcc($message->getBcc());
        $this->setFrom($message->getFrom());
        $this->setHeaders($message->getHeaders());
        if ($message->getReplyTo()) {
            $this->setReplyTo($message->getReplyTo());
        } else {
            $this->setReplyTo($message->getTo());
        }
        $this->setContentType($message->getHeaders()->get('Content-Type')->getValue());

        return $this;
    }

    /**
     * Get message.
     *
     * @return \Swift_Message
     */
    public function getMessage()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($this->getSubject())
            ->setFrom($this->getFrom())
            ->setTo($this->getTo())
            ->setCc($this->getCc())
            ->setBcc($this->getBcc())
            ->setReplyTo($this->getReplyTo())
            ->setBody($this->getBody(), 'text/html');
        $messageHeaders = $message->getHeaders();
        $dbHeaders = $this->getHeaders();
        foreach ($dbHeaders as $header => $value) {
            if (!$messageHeaders->has($header)) {
                $messageHeaders->addTextHeader($header, $value);
            }
        }
        foreach ($this->attachments as $attachment) {
            $message
                ->attach(\Swift_Attachment::fromPath($attachment->getPathName())
                    ->setFilename($attachment->getClientOriginalName())
                );
        }

        return $message;
    }

    /**
     * get ContentType.
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * set ContentType.
     *
     * @param string $contentType The contentType to define
     *
     * @return Mail
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * get From.
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * set From.
     *
     * @param string $from The author's email
     *
     * @return Mail
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * get To.
     *
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * set To.
     *
     * @param string $to The email to send
     *
     * @return Mail
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return array
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @param array $cc
     */
    public function setCc($cc)
    {
        $this->cc = $cc;
    }

    /**
     * @return array
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @param array $bcc
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;
    }

    /**
     * get ReplyTo email.
     *
     * @return string
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * set ReplyTo.
     *
     * @param string $replyTo The email to reply
     *
     * @return Mail
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    /**
     * get Subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * set Subject.
     *
     * @param string $subject The Subject to define
     *
     * @return Mail
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * get Body.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * set Body.
     *
     * @param string $body The Body to define
     *
     * @return Mail
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * get Status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * set Status.
     *
     * @param string $status The Status to define
     *
     * @return Mail
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * get Headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return (array) $this->headers;
    }

    /**
     * set Headers.
     *
     * @param HeaderSet $headers The headers of the email
     *
     * @return Mail
     */
    public function setHeaders($headers)
    {
        $headerNames = $headers->listAll();
        $headersArray = [];
        foreach ($headerNames as $headerName) {
            if (!$this->isDefaultHeader($headerName) && $headers->get($headerName) instanceof \Swift_Mime_Headers_UnstructuredHeader) {
                $headersArray[$headerName] = $headers->get($headerName)->getValue();
            }
        }
        $this->headers = count($headersArray) ? $headersArray : null;

        return $this;
    }

    /**
     * @return \AppVentus\Awesome\SpoolMailerBundle\Entity\Attachment[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    public function addAttachment($attachment)
    {
        if ($attachment instanceof \Swift_Attachment) {
            $swiftAttachment = $attachment;
            $attachment = new Attachment();
            $attachment->setSwiftAttachment($swiftAttachment);
        }
        if ($attachment instanceof Attachment) {
            $this->attachments->add($attachment);
            $attachment->setMail($this);
        }
    }

    /**
     * @param \AppVentus\Awesome\SpoolMailerBundle\Entity\Attachment[] $attachments
     */
    public function setAttachments($attachments)
    {
        foreach ($attachments as $attachment) {
            $this->addAttachment($attachment);
        }
    }

    /**
     * @param string
     *
     * @return bool
     */
    private function isDefaultHeader($headerName)
    {
        $defaultHeaderNames = [
        'message-id',
        'date',
        'subject',
        'from',
        'to',
        'reply-to',
        'bcc',
        'mime-version',
        'content-type',
        'content-transfer-encoding',
      ];

        return in_array($headerName, $defaultHeaderNames);
    }
}
