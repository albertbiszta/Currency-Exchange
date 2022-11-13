<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Payment;
use App\Enum\PaymentType;
use App\Exception\WithdrawException;
use App\Service\Payment\WithdrawService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WithdrawController extends PaymentController
{
    protected const VIEW_TITLE = 'Make withdraw';

    public function __construct(private readonly WithdrawService $withdrawService)
    {
    }


    #[Route('/withdraw', name: 'withdraw')]
    public function createWithdraw(Request $request): Response
    {
       return $this->createPayment($request, PaymentType::WITHDRAW);
    }

    protected function handle(Payment $payment): RedirectResponse
    {
        try {
            $this->withdrawService->handle($payment);
            $this->addFlash('success', 'Withdraw completed successfully.');
        } catch (WithdrawException $e) {
            $this->addFlash('error', $e->getMessage());
        } catch (\Exception) {
            $this->addFlash('error', 'An error has occurred during the withdraw.');
        }

        return $this->redirectToRoute('withdraw');
    }
}
