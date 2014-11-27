<?php

namespace Application\Bundle\FrontBundle\Helper;

class EmailHelper
{
    public function sendEmail($renderedTemplate, $subject, $fromEmail, $toEmail)
    {        
        $body = $renderedTemplate;

        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail)
                ->setBody($body);

        $this->get('mailer')->send($message);
    }
}
