<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ExchangeFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $form = $this->createForm(ExchangeFormType::class)->remove('submit');

        return $this->render('home/index.html.twig', [
            'exchange_form' => $form->createView(),
        ]);
    }
}
