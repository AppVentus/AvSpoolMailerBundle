<?php
namespace AppVentus\Awesome\SpoolMailerBundle\Entity;

use WhiteOctober\SwiftMailerDBBundle\EmailInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 *  AppVentus\Awesome\SpoolMailerBundle\Entity\Mail
 *
 * @ORM\Table(name="mail")
 * @ORM\Entity
 */
class Mail implements EmailInterface{


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
     * @var string $from
     *
     * @ORM\Column(name="sender", type="string", length=255, nullable=true)
     */
    private $from;
    /**
     * @var string $to
     *
     * @ORM\Column(name="recipient", type="string", length=255, nullable=true)
     */
    private $to;
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
     * @var datetime $send_date
     *
     * @ORM\Column(name="send_date", type="datetime", nullable=true)
     */
    protected $sendDate;
    /**
     * @var datetime $creation_date
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=true)
     */
    protected $creationDate;
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
     * Set creationDate
     *
     * @param string $creationDate
     * @return Event
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * Get start_date
     *
     * @return string
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }
    /**
     * Set type
     *
     * @param string $type
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


    public function setMessage($message){
    	$this->setSubject($message->getSubject());
    	$this->setBody($message->getBody());
    	$this->setTo(serialize($message->getTo()));
    	$this->setFrom(serialize($message->getFrom()));
    	$this->setContentType($message->getHeaders()->get('Content-Type')->getValue());
    }
    public function getMessage(){

    	$message = \Swift_Message::newInstance()
            ->setSubject($this->getSubject())
            ->setFrom(unserialize($this->getFrom()))
            ->setTo(unserialize($this->getTo()))
            ->setBody($this->getBody(), $this->getContentType())
            ;
        return $message;
    }

    public function getContentType(){
    	return $this->contentType;
    }
    public function setContentType($contentType){
    	$this->contentType = $contentType;
    }
    public function getFrom(){
    	return $this->from;
    }
    public function setFrom($from){
    	$this->from = $from;
    }
    public function getTo(){
    	return $this->to;
    }
    public function setTo($to){
    	$this->to = $to;
    }
    public function getSubject(){
    	return $this->subject;
    }
    public function setSubject($subject){
    	$this->subject = $subject;
    }
    public function getBody(){
    	return $this->body;
    }
    public function setBody($body){
    	$this->body = $body;
    }

    public function getStatus(){
    	return $this->status;
    }
    public function setStatus($status){
    	$this->status = $status;
    }

}

?>
