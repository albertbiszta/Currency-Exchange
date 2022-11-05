<?php

namespace App\Controller;

use App\Exception\ExchangeException;
use App\Form\ExchangeFormType;
use App\Service\ExchangeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeController extends AbstractController
{
    public function __construct(private ExchangeService $exchangeService)
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


    #[Route('/exchange', name: 'exchange')]
    public function createExchange(Request $request): Response
    {
        $form = $this->createForm(ExchangeFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $formData = $this->exchangeService->getDataFromForm($form);  // TODO - use getDataFromForm() in service
                $this->exchangeService->createExchange($formData);
                $this->addFlash('success', 'Currency exchange completed successfully.');
            } catch (ExchangeException $e) {
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
