<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private Environment $twig, private Security $security)
    {
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
        if ($user = $this->security->getUser()) {
            $this->twig->addGlobal('accounts', $user->getUserAccounts());
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [ControllerEvent::class => 'onControllerEvent',];
    }
}
