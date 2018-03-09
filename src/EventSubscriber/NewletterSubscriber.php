<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 06/03/2018
 * Time: 14:28
 */

namespace App\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class NewletterSubscriber implements EventSubscriberInterface
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::RESPONSE => 'onKernelResponse'

        ];
    }

    public function onKernelRequest (GetResponseEvent $event)
    {

        // On s'assure que la requete viens de l'utilisateur et non de Symfony !
        if(!$event->isMasterRequest() || $event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        $this->session->set('countUserView',$this->session->get('countUserView', 0) + 1);

        // On va pouvoir inviter notre utilisateur au bout de la 3eme page consulté
        if ($this->session->get('countUserView') === 3) {
            $this->session->set('inviteUser', true);
        }

    }

    public function onKernelResponse (FilterResponseEvent $event)
    {
        // On s'assure que la requete viens de l'utilisateur et non de Symfony !
        if(!$event->isMasterRequest() || $event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        if($this->session->get('countUserView') === 3) {
            $this->session->set('inviteUser', false);
        }
    }


}