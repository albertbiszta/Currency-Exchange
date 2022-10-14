<?php

namespace App\Controller;

use App\Service\CurrencyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CurrencyController extends AbstractController
{
    #[Route('/currency/chart/{currency}', name: 'chart')]
    public function chart(string $currency): Response
    {
        return $this->render('currency/chart.html.twig', [
            'chart' => CurrencyService::getChart($currency, 7),
        ]);
    }

    #[Route('/currency/get-chart/currency/{currency}/number-of-days/{numberOfDays}')]
    public function chartJson(string $currency, int $numberOfDays): JsonResponse
    {
        return new JsonResponse(CurrencyService::getLastDaysRatesForCurrency($currency, $numberOfDays));
    }
}
