<?php

namespace App\Controller;

use App\Service\CurrencyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/api/currency/chart')]
    public function chartData(Request $request): JsonResponse
    {
        if ($request->isMethod('POST')) {
            $data = $this->getPostData($request);
            return new JsonResponse(CurrencyService::getLastDaysRatesForCurrency($data['currency'], $data['numberOfDays']));
        }
    }

    #[Route('/api/currency/conversion', name: 'currency_conversion')]
    public function conversionResult(Request $request): JsonResponse
    {
        if ($request->isMethod('POST')) {
            return new JsonResponse(CurrencyService::getConversion($this->getPostData($request)));
        }
    }

    private function getPostData(Request $request): array
    {
        return json_decode($request->getContent(), true);
    }
}
