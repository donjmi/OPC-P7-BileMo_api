<?php

namespace App\Events;

use ApiPlatform\core\EventListener\EventPriorities;
use Api\Entity\User;
use Symfony\compenent\EventDispatcher\EventSubcriberInterface;
use Symfony\compenent\HttpKernel\Event\ViewEvent;
use Symfony\compenent\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordEncoderSubscriber implements EventSubcriberInterface
{

    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['encodePassword', EventPriorities::PRE_WRITE]
        ];
    }

    public function encodePassword(ViewEvent $event){
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof User && $method ==="POST") {
            $hash = $this->passwordEncoder()->encodePassword($result, $result->getPassword());
            $result->setPassword($hash);
        }
    }

}


