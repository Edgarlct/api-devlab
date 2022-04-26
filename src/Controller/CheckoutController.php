<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    #[Route(
        path: '/api/checkout',
        name: 'app_checkout',
        methods: ['GET'])]
    public function index(): Response
    {
        return $this->json('ok')->setStatusCode(200);
    }
}
