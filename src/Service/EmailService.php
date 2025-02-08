<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string
     * @param string
     * @param string
     * @return void
     */
    public function sendWelcomeEmployeeEmail(string $to, string $name, string $position): void
    {
        $email = (new Email())
            ->from('no-reply@unow.com')
            ->to($to)
            ->subject('Welcome to our company')
            ->text(sprintf('Hello %s, welcome to our company!. to position %s', $name, $position));

        $this->mailer->send($email);
    }
}