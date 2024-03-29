<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserProfileController extends AbstractController
{
    public function __construct(private readonly Security $security)
    {
    }

    #[Route('/profile', name: 'user_profile')]
    public function index(): Response
    {
        return $this->render('user_profile/index.html.twig', [
            'accounts' => $this->security->getUser()->getUserAccounts(),
        ]);
    }

    #[Route('/profile/exchanges', name: 'user_exchanges')]
    public function exchanges(): Response
    {
        return $this->render('user_profile/exchanges.html.twig', [
            'exchanges' => $this->security->getUser()->getExchanges(),
        ]);
    }

    #[Route('/profile/payments', name: 'user_payments')]
    public function payments(): Response
    {
        return $this->render('user_profile/payments.html.twig', [
            'payments' => $this->security->getUser()->getPayments(),
        ]);
    }
}
