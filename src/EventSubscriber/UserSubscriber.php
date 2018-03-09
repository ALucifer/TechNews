<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 06/03/2018
 * Time: 16:09
 */

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class UserSubscriber implements EventSubscriberInterface
{
    private $em;

    /**
     * On injecte l'EntityManager pour pouvoir sauvegarder en base
     * UserSubscriber constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityLogin'
        ];
    }

    public function onSecurityLogin (InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        $user->setDerniereConnexion(new \DateTime());
        $this->em->persist($user);
        $this->em->flush();
    }

}