<?php

namespace App\Events;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PasswordEncoderSubscriber implements EventSubscriberInterface
{
/** @var UserPasswordEncoderInterface */
 private $encoder;

 public function __construct(UserPasswordEncoderInterface $encoder)
 { 
    $this->encoder = $encoder;
}

public static function getSubscribedEvents()
{        
    return [KernelEvents::VIEW => ['encodePassword', EventPriorities::PRE_WRITE]];
 }

    public function encodePassword(ViewEvent $event):void
    {    
        $user = $event->getControllerResult();
        
        $method = $event->getRequest()->getMethod(); // POST, GET, PUT, ...
        if ($user instanceof User && $method === "POST") 
        {
            $hash = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
        }
}

}