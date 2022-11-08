<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Enum\Currency;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
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

    #[ArrayShape([ControllerEvent::class => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [ControllerEvent::class => 'onControllerEvent',];
    }
}
