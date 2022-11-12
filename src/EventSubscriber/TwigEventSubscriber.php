<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Enum\Currency;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly Environment $twig, private readonly Security $security)
    {
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
        if ($user = $this->security->getUser()) {
            $this->twig->addGlobal('accounts', $user->getUserAccounts());
        }
        $this->twig->addGlobal('currencyChoices', Currency::getChoices());
    }

    #[ArrayShape([ControllerEvent::class => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [ControllerEvent::class => 'onControllerEvent',];
    }
}
