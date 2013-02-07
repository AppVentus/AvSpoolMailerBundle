<?php

namespace AppVentus\Awesome\SpoolMailerBundle\Mailer;

use AppVentus\JobBoardBundle\Entity\Mail;
use WhiteOctober\SwiftMailerDBBundle\EmailInterface;

/**
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class InstantMailer extends \Swift_Mailer
{
    protected $mailer;

    public function __construct($em, $transport)
    {

    	$this->em = $em;
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
        $mail = new Mail;
        $mail->setMessage($newMessage);
        $mail->setType('instant');
        $mail->setCreationDate(new \DateTime());
        $mail->setStatus(EmailInterface::STATUS_COMPLETE);
        $this->em->persist($mail);
        $this->em->flush();
  	return parent::send($newMessage, $failedRecipients);
  }

}
