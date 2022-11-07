<?php

namespace App\EventSubscriber;

use App\Enum\Currency;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class CurrencyChoicesEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly Environment $twig)
    {
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
        $this->twig->addGlobal('currencyChoices', Currency::getFormChoices());
    }

    public static function getSubscribedEvents(): array
    {
        return [ControllerEvent::class => 'onControllerEvent',];
    }
}
