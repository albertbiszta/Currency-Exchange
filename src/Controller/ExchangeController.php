<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Exchange;
use App\Exception\InsufficientFoundsException;
use App\Form\ExchangeFormType;
use App\Service\ExchangeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeController extends AbstractController
{
    public function __construct(private readonly ExchangeService $exchangeService)
    {
    }

    #[Route('/exchange', name: 'exchange')]
    public function createExchange(Request $request): Response
    {
        $form = $this->createForm(ExchangeFormType::class, new Exchange());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->exchangeService->createExchange($form->getData());
                $this->addFlash('success', 'Currency exchange completed successfully.');
            } catch (InsufficientFoundsException $e) {
                $this->addFlash('error', $e->getMessage());
            } catch (\Exception) {
                $this->addFlash('error', 'An error has occurred during the exchange.');
            }

            return $this->redirectToRoute('exchange');
        }

        return $this->render('exchange/index.html.twig', [
            'exchange_form' => $form->createView(),
        ]);
    }
}
