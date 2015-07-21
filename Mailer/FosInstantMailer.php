<?php

namespace AppVentus\Awesome\SpoolMailerBundle\Mailer;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;

class FosInstantMailer implements MailerInterface
{
    protected $mailer;
    protected $router;
    protected $templating;
    protected $twig;
    protected $parameters;

    public function __construct($mailer, RouterInterface $router, EngineInterface $templating, \Twig_Environment $twig, array $parameters)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
        $this->twig = $twig;
        $this->parameters = $parameters;
    }

    public function sendConfirmationEmailMessage(UserInterface $user)
    {

        $template = $this->parameters['confirmation.template'];
        $loadTemplate = $this->twig->loadTemplate($template);
        $subject = $loadTemplate->renderBlock('subject', array());

        if(!$subject) {
            $subject = "Confirmation d'inscription";
        }

        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), true);
        $rendered = $this->templating->render($template, array(
            'user' => $user,
            'confirmationUrl' =>  $url
        ));
        $this->sendEmailMessage($rendered, $this->parameters['from_email']['confirmation'], $user->getEmail(), $subject);
    }

    public function sendResettingEmailMessage(UserInterface $user)
    {

        $template = $this->parameters['resetting.template'];
        $loadTemplate = $this->twig->loadTemplate($template);
        $subject = $loadTemplate->renderBlock('subject', array());

        if(!$subject) {
            $subject = "Re-définition du mot de passe";
        }

        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), true);
        $rendered = $this->templating->render($template, array(
            'user' => $user,
            'confirmationUrl' => $url
        ));
        $this->sendEmailMessage($rendered, $this->parameters['from_email']['resetting'], $user->getEmail(), $subject);
    }

    public function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail, $subject = null)
    {
        // Render the email, use the first line as the subject, and the rest as the body
        $renderedLines = explode("\n", trim($renderedTemplate));
        if ($subject == null) {
            $subject = $renderedLines[0];
        }
        $body = implode("\n", array_slice($renderedLines, 1));

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }
}
