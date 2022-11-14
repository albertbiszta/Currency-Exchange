<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Exchange;
use App\Enum\Currency;
use App\Exception\CurrencyException;
use App\Service\ChartService;
use App\Service\CurrencyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CurrencyController extends AbstractController
{
    #[Route('/currency/chart/{currencySlug}', name: 'chart')]
    #[Route('/currency/chart/{currencySlug}/days/{numberOfDays}', name: 'chart_with_days')]
    public function chart(string $currencySlug, int $numberOfDays = CurrencyService::DEFAULT_NUMBER_OF_DAYS): Response
    {
        try {
            $currency = Currency::getBySlug($currencySlug);
            if ($numberOfDays > ($numberOfDaysLimit = CurrencyService::LIMIT_OF_NUMBER_OF_DAYS_ON_CHART)) {
                return $this->redirectToChart($currencySlug, $numberOfDaysLimit);
            }

            return $this->render('currency/chart.html.twig', [
                'chart' => ChartService::getChart($currency, $numberOfDays),
                'numberOfDays' => $numberOfDays,
                'currencyPercentageChangeMessage' => CurrencyService::getPercentageChangeMessage($currency, $numberOfDays),
            ]);
        } catch (CurrencyException) {
            if ($currency = Currency::tryFrom($currencySlug)) {
                return $this->redirectToChart($currency->getSlug(), $numberOfDays);
            }

            return $this->redirectToRoute('home');
        }
    }

    #[Route('/api/currency/chart')]
    public function chartData(Request $request): JsonResponse
    {
        $data = $this->getPostData($request);

        return new JsonResponse(ChartService::getJsConfig(Currency::from($data['currencyCode']), $data['numberOfDays']));
    }

    #[Route('/api/currency/conversion', name: 'currency_conversion')]
    public function conversionResult(Request $request): JsonResponse
    {
        $formData = $this->getPostData($request);
        $primaryCurrency = Currency::from($formData[Exchange::ATTRIBUTE_PRIMARY_CURRENCY]);
        $targetCurrency = Currency::from($formData[Exchange::ATTRIBUTE_TARGET_CURRENCY]);
        $amount = $formData[Exchange::ATTRIBUTE_AMOUNT];
        $exchange = (new Exchange())->setInitAttributes($primaryCurrency, $targetCurrency, $amount);
        $conversionResult = CurrencyService::getConversion($exchange);

        return new JsonResponse($primaryCurrency->getNameWithAmount($amount) . ' = ' . $targetCurrency->getNameWithAmount($conversionResult));
    }

    private function redirectToChart(string $currencySlug, int $numberOfDays): RedirectResponse
    {
        if ($numberOfDays === CurrencyService::DEFAULT_NUMBER_OF_DAYS) {
            return $this->redirectToRoute('chart', [
                'currencySlug' => $currencySlug,
            ]);
        }

        return $this->redirectToRoute('chart_with_days', [
            'currencySlug' => $currencySlug,
            'numberOfDays' => $numberOfDays,
        ]);
    }

    private function getPostData(Request $request): array
    {
        return json_decode($request->getContent(), true);
    }
}
