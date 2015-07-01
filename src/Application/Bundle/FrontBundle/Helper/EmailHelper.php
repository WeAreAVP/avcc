<?php
/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@avpreserve.com>
 * @author   Rimsha Khalid <rimsha@avpreserve.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.avpreserve.com
 */
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
                ->setContentType("text/html")
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail)
                ->setBody($body, 'text/html');

        $this->container->get('mailer')->send($message);
    }
}
