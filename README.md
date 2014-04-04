AvSpoolMailerBundle
===================

Allow you to store your mails as a spool and send transactionnal direct mails or store it in db

-------------------
1) Installation

This bundle is on Packagist here :
    https://packagist.org/packages/appventus/spoolmailerbundle

2) Configuration :

Import this in your config.yml :
    - { resource: @AvSpoolMailerBundle/Resources/config/config.yml }

Always in config.yml, overwrite the sender mail config:

    av_awesome_spool_mailer:
        contact_addresses:
          admin:
            address: [yours]
            name: [yours]
          noreply:
            address: [yours]
            name: [yours]
          ...

Add your owns, each row inside contact addresses will automatically be accessible in the parameter bag following this scheme :

example :

    contact_addresses_**KEY**_address


3) Usage :

You can spool message in db with :
    $this->get('white_october.swiftmailer_db.spool')->queueMessage($message);

You can send instant email in controller with :
    $this->get('instant_mailer')->send($message);

To send spooled messages, call :
    php app/console swiftmailer:spool:send


