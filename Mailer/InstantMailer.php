<?php

namespace AppVentus\Awesome\SpoolMailerBundle\Mailer;

use WhiteOctober\SwiftMailerDBBundle\EmailInterface;

/**
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class InstantMailer extends \Swift_Mailer
{
    protected $mailer;

    /**
     * @var string
     */
    protected $entityClass;
    public function __construct($em, $transport, $entityClass)
    {

    	$this->em = $em;

        $obj = new $entityClass;
        if (!$obj instanceof EmailInterface) {
            throw new \InvalidArgumentException("The entity class '{$entityClass}'' does not extend from EmailInterface");
        }

        $this->entityClass = $entityClass;
    	parent::__construct($transport);
    }

  public function send(\Swift_Mime_Message $message, &$failedRecipients = null)
  {
        //create a new message object to set content type as html because fosuser mailer create messages in crappy plain text
        $newMessage = \Swift_Message::newInstance()
            ->setSubject($message->getSubject())
            ->setFrom($message->getFrom())
            ->setTo($message->getTo())
            ->setBody($message->getBody(), 'text/html')
            ;
            $newMessage->setReplyTo($message->getReplyTo());

        $mail = new $this->entityClass;
        $mail->setMessage($newMessage);
        $mail->setType('instant');
        $mail->setStatus(EmailInterface::STATUS_COMPLETE);
        $this->em->persist($mail);
        $this->em->flush();
  	return parent::send($newMessage, $failedRecipients);
  }

}
