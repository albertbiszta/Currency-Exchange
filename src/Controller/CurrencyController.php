<?php

namespace App\Controller;

use App\Service\CurrencyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CurrencyController extends AbstractController
{
    public function __construct(private CurrencyService $currencyService)
    {
    }

    #[Route('/currency/chart/{currency}', name: 'chart')]
    public function chart(string $currency): Response
    {
        return $this->render('currency/chart.html.twig', ['chart' => $this->currencyService->getChart($currency, 7)]);
    }

    #[Route('/currency/get-chart/{currency}')]
    public function chartJson(string $currency): JsonResponse
    {
        return new JsonResponse($this->currencyService->getLastDaysRatesForCurrency($currency, 7));
    }
}
