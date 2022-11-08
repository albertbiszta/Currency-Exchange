<?php

namespace App\Controller;

use App\Entity\Exchange;
use App\Enum\Currency;
use App\Service\CurrencyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CurrencyController extends AbstractController
{
    #[Route('/currency/chart/{currencyCode}', name: 'chart')]
    #[Route('/currency/chart/{currencyCode}/days/{numberOfDays}', name: 'chart_with_days')]
    public function chart(string $currencyCode, int $numberOfDays = 7): Response
    {
        if (!$currency = Currency::from($currencyCode)) {
            return $this->redirectToRoute('home');
        }

        if ($numberOfDays > ($numberOfDaysLimit = CurrencyService::LIMIT_OF_NUMBER_OF_DAYS_ON_CHART)) {
           return $this->redirectToRoute('chart_with_days', [
                'currency' => $currencyCode,
                'numberOfDays' => $numberOfDaysLimit,
            ]);
        }

        return $this->render('currency/chart.html.twig', [
            'chart' => CurrencyService::getChart($currency, $numberOfDays),
            'numberOfDays' => $numberOfDays,
        ]);
    }

    #[Route('/api/currency/chart')]
    public function chartData(Request $request): JsonResponse
    {
        $data = $this->getPostData($request);
        return new JsonResponse(CurrencyService::getLastDaysRatesForCurrency(Currency::from($data['currency']), $data['numberOfDays']));
    }

    #[Route('/api/currency/conversion', name: 'currency_conversion')]
    public function conversionResult(Request $request): JsonResponse
    {
        $formData = $this->getPostData($request);
        $primaryCurrency = Currency::from($formData[Exchange::ATTRIBUTE_PRIMARY_CURRENCY]);
        $targetCurrency = Currency::from($formData[Exchange::ATTRIBUTE_TARGET_CURRENCY]);
        $amount = $formData[Exchange::ATTRIBUTE_AMOUNT];
        $exchange = new Exchange();
        $exchange
            ->setAmount($amount)
            ->setPrimaryCurrency($primaryCurrency)
            ->setTargetCurrency($targetCurrency);
        $conversionResult = CurrencyService::getConversion($exchange);

        return new JsonResponse($primaryCurrency->getNameWithAmount($amount) . ' = ' . $targetCurrency->getNameWithAmount($conversionResult));
    }

    private function getPostData(Request $request): array
    {
        return json_decode($request->getContent(), true);
    }
}
