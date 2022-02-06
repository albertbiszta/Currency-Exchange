<?php

namespace App\Controller;

use App\Form\ExchangeFormType;
use App\Service\ExchangeService;
use App\Service\UserAccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeController extends AbstractController
{
    public function __construct(private ExchangeService $exchangeService, private UserAccountService $userAccountService)
    {
    }

    #[Route('/calculator', name: 'calculator')]
    public function calculator(): Response
    {
        $form = $this->createForm(ExchangeFormType::class)->remove('submit');

        return $this->render('exchange/calculator.html.twig', [
            'exchange_form' => $form->createView(),
        ]);
    }

    #[Route('/get-conversion-result', name: 'conversion_result')]
    public function getConversionResult(Request $request): JsonResponse
    {
        $result = $this->exchangeService->getCurrencyConversion(json_decode($request->getContent(), true));
        return $this->json($result);
    }

    #[Route('/exchange', name: 'exchange')]
    public function createExchange(Request $request): Response
    {
        $form = $this->createForm(ExchangeFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = [];
            foreach (['primaryCurrency', 'targetCurrency', 'amount'] as $field) {
                $formData[$field] = $form[$field]->getData();
            }
            $this->exchangeService->createExchange($formData);
            $this->addFlash('success', 'Currency exchange completed successfully');
            return $this->redirectToRoute('exchange');

              //  $this->addFlash('error', 'Insufficient funds!');
        }

        return $this->render('exchange/index.html.twig', [
            'exchange_form' => $form->createView(),
        ]);
    }

    #[Route('/test', name: 'test')]
    public function test(): Response
    {
       return new Response((string) $this->userAccountService->isAccountBalanceSufficient('usd', 12));
    }

}
