<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 07/03/2018
 * Time: 11:01
 */

namespace App\Service\Security;

use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Templating\PhpEngine;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment as Environment;


class MyMailer
{

    private $mailer;
    private $router;
    private $template;


    public function __construct(\Swift_Mailer $mailer,UrlGeneratorInterface $router, Environment $template)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->template = $template;
    }

    public function forgotPasswordMailer($email, $token)
    {
        $url = $this->router->generate('security_reset_password', ['token' => $token], $this->router::ABSOLUTE_URL);

        $message = (new \Swift_Message('Mot de passe oublié'))
            ->setFrom($email)
            ->setTo('test@test.com')
            ->setBody(
                $this->template->render('emails/forgot.html.twig',[
                    'lien' => $url
                ]),
                'text/html'
            );

        $this->mailer->send($message);
    }

    public function registrerMailer()
    {

    }

}