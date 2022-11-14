<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Payment;
use App\Enum\PaymentType;
use App\Service\Payment\DepositService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DepositController extends PaymentController
{
    protected const VIEW_TITLE = 'Make deposit';

    public function __construct(private readonly DepositService $depositService)
    {
    }

    #[Route('/deposit', name: 'deposit')]
    public function createDeposit(Request $request): Response
    {
       return $this->createPayment($request, PaymentType::DEPOSIT);
    }

    #[Route('/deposit/complete/{paymentId}', name: 'deposit_complete')]
    public function complete(int $paymentId): Response
    {
        $this->depositService->complete($paymentId);

        return $this->redirectToRoute('deposit_success');
    }

    #[Route('/deposit/success', name: 'deposit_success')]
    public function success(): Response
    {
        return $this->render('payment/success.html.twig');
    }

    #[Route('/deposit/cancel', name: 'deposit_cancel')]
    public function cancel(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }

    protected function handleFormSubmit(Payment $payment): RedirectResponse
    {
        $session = $this->depositService->handle($payment);
        return $this->redirect($session->url, 303);
    }
}
