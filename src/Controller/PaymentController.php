<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Form\PaymentFormType;
use App\Repository\PaymentRepository;
use App\Service\PaymentService;
use App\Service\UserAccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    public function __construct(private PaymentService $paymentService, private PaymentRepository $paymentRepository, private UserAccountService $userAccountService)
    {
    }

    #[Route('/payment', name: 'payment')]
    public function createPayment(Request $request): Response
    {
        $form = $this->createForm(PaymentFormType::class, new Payment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session = $this->paymentService->paymentHandle($form->getData());
            return $this->redirect($session->url, 303);
        }
        return $this->render('payment/payment.html.twig', ['payment_form' => $form->createView()]);
    }

    #[Route('/payment/status-change/{paymentId}', name: 'payment_status_change')]
    public function changePaymentStatus($paymentId): Response
    {
        $payment = $this->paymentRepository->findOneBy(['id' => $paymentId]);
        $this->paymentService->setPaymentAsCompleted($payment);
        $this->userAccountService->addToAccount($payment->getCurrency(), $payment->getAmount());

        return $this->redirectToRoute('payment_success_url');
    }

    #[Route('/payment/success-url', name: 'payment_success_url')]
    public function successUrl(): Response
    {
        return $this->render('payment/success.html.twig', []);
    }

    #[Route('/payment/cancel-url', name: 'payment_cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('payment/cancel.html.twig', []);
    }
}
