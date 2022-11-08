<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Enum\PaymentType;
use App\Exception\WithdrawException;
use App\Form\PaymentFormType;
use App\Service\PaymentService;
use App\Service\UserAccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    public function __construct(private readonly PaymentService $paymentService)
    {
    }

    #[Route('/deposit', name: 'deposit')]
    public function createDeposit(Request $request): Response
    {
        $form = $this->createForm(PaymentFormType::class, new Payment(PaymentType::DEPOSIT));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session = $this->paymentService->handleDeposit($form->getData());
            return $this->redirect($session->url, 303);
        }
        return $this->render('payment/payment.html.twig', [
            'payment_form' => $form->createView(),
            'title' => 'Make deposit',
        ]);
    }

    #[Route('/withdraw', name: 'withdraw')]
    public function createWithdraw(Request $request): Response
    {
        $form = $this->createForm(PaymentFormType::class, new Payment(PaymentType::WITHDRAW));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->paymentService->handleWithdraw($form->getData());
                $this->addFlash('success', 'Withdraw completed successfully.');
            } catch (WithdrawException $e) {
                $this->addFlash('error', $e->getMessage());
            } catch (\Exception) {
                $this->addFlash('error', 'An error has occurred during the withdraw.');
            }

            return $this->redirectToRoute('withdraw');
        }
        return $this->render('payment/payment.html.twig', [
            'payment_form' => $form->createView(),
            'title' => 'Make withdraw',
        ]);
    }

    #[Route('/deposit/complete/{paymentId}', name: 'deposit_complete')]
    public function completeDeposit($paymentId): Response
    {
        $this->paymentService->completeDeposit($paymentId);

        return $this->redirectToRoute('deposit_success_url');
    }

    #[Route('/deposit/success-url', name: 'deposit_success_url')]
    public function successUrl(): Response
    {
        return $this->render('payment/success.html.twig');
    }

    #[Route('/deposit/cancel-url', name: 'deposit_cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }
}
