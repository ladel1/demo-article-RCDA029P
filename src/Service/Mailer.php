<?php 

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class Mailer{


    private $mailer;
    private $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig=$twig;
    }


    public function send($from,$to,$sub,$content){

        $email = new Email();
        $email->from($from)
            ->to($to)
            ->subject($sub)
            ->html($this->twig->render("email/index.html.twig",compact("sub","content")));
        $this->mailer->send($email);

    }





}