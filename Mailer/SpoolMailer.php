<?php

namespace AppVentus\Awesome\SpoolMailerBundle\Mailer;

use WhiteOctober\SwiftMailerDBBundle\Spool\DatabaseSpool;

class SpoolMailer extends \Swift_Mailer
{
    protected $databaseSpool;

    /**
     * SpoolMailer constructor.
     *
     * @param \Swift_Transport $transport
     * @param DatabaseSpool    $databaseSpool
     */
    public function __construct(\Swift_Transport $transport,
        DatabaseSpool $databaseSpool
    ) {
        $this->databaseSpool = $databaseSpool;
        parent::__construct($transport);
    }

    /**
     * @param \Swift_Mime_Message $message
     * @param null                $failedRecipients
     *
     * @throws \Swift_IoException
     *
     * @return bool
     */
    public function send(\Swift_Mime_Message $message, &$failedRecipients = null)
    {
        return $this->databaseSpool->queueMessage($message);
    }
}
