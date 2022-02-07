<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $user;

    public function __construct(private Environment $twig, Security $security)
    {
        $this->user = $security->getUser();
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
        $this->user && $this->twig->addGlobal('accounts', $this->user->getUserAccounts());
    }

    public static function getSubscribedEvents(): array
    {
        return [ControllerEvent::class => 'onControllerEvent',];
    }
}
