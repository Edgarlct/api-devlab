<?php

namespace App\Controller;

use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    #[Route(
        path: '/api/events/{id}/participant',
        name: 'app_checkout',
        methods: ['GET'])]



    public function index(Request $request, TicketRepository $ticketRepository): Response
    {
        $ticketList = $ticketRepository->findBy(['event' => $request->get('id')]);
        if (empty($ticketList)){
            return $this->json("Event not found")->setStatusCode(404);
        }
        return $this->json($ticketList)->setStatusCode(200);
    }

}

