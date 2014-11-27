<?php

namespace Application\Bundle\FrontBundle\Helper;

class EmailHelper
{
    private $container;
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function sendEmail($renderedTemplate, $subject, $fromEmail, $toEmail)
    {        
        $body = $renderedTemplate;

        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail)
                ->setBody($body);

        $this->container->get('mailer')->send($message);
    }
}
