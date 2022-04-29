<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserMe extends AbstractController
{
    #[Route(
        path: '/api/user/me',
        name: 'app_me',
        methods: ['GET'])]



    public function index(): Response
    {
        return $this->json($this->getUser())->setStatusCode(200);
    }

}

