<?php
namespace AppVentus\Awesome\SpoolMailerBundle\Entity;

use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WhiteOctober\SwiftMailerDBBundle\EmailInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * AppVentus\Awesome\SpoolMailerBundle\Entity\Mail
 *
 * @ORM\Table(name="mail")
 * @ORM\Entity
 */
class Mail implements EmailInterface
{
    use TimestampableEntity;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int $status
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var string $body
     *
     * @ORM\Column(name="mail_body", type="text")
     */
    private $body;

    /**
     * @var array $from
     *
     * @ORM\Column(name="sender", type="array", length=255, nullable=true)
     */
    private $from;

    /**
     * @var array $to
     *
     * @ORM\Column(name="recipient", type="array", length=255, nullable=true)
     */
    private $to;

    /**
     * @var array $replyTo
     *
     * @ORM\Column(name="reply_to", type="array", length=255, nullable=true)
     */
    private $replyTo;

    /**
     * @var array $attachments
     *
     * @ORM\Column(name="attachments", type="array", nullable=true)
     */
    private $attachments = [];

    /**
     * @var string $subject
     *
     * @ORM\Column(name="subject", type="text", nullable=true)
     */
    private $subject;

    /**
     * @var string $subject
     *
     * @ORM\Column(name="content_type", type="text", nullable=true)
     */
    private $contentType;

    /**
     * @var \DateTime $send_date
     *
     * @ORM\Column(name="send_date", type="datetime", nullable=true)
     */
    protected $sendDate;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    protected $type;

    /**
     * Set sendDate
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
     * Get start_date
     *
     * @return string
     */
    public function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * Set type
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
     * Get start_date
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set message
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
        $this->setFrom($message->getFrom());
        if ($message->getReplyTo()) {
            $this->setReplyTo($message->getReplyTo());
        } else {
            $this->setReplyTo($message->getTo());
        }
        $this->setContentType($message->getHeaders()->get('Content-Type')->getValue());

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {

        $message = \Swift_Message::newInstance()
            ->setSubject($this->getSubject())
            ->setFrom($this->getFrom())
            ->setTo($this->getTo())
            ->setReplyTo($this->getReplyTo())
            ->setBody($this->getBody(), 'text/html');

        foreach ($this->attachments as $attachment) {
            $message->attach($attachment);
        }

        return $message;
    }

    /**
     * get ContentType
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * set ContentType
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
     * get From
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * set From
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
     * get To
     *
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * set To
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
     * get ReplyTo email
     *
     * @return string
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * set ReplyTo
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
     * get Subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * set Subject
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
     * get Body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * set Body
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
     * get Status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * set Status
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
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * @param string $attachment
     */
    public function addAttachment($attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
    }

}
