<?php

namespace App\Controller;

use App\Repository\EventRepository;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    #[Route(
        path: '/api/events/association/{id}',
        name: 'app_event_asso',
        methods: ['GET'])]

    public function index(Request $request, EventRepository $eventRepository): Response
    {
        $date = new \DateTimeImmutable('', new DateTimeZone('Europe/Paris'));
        $eventList = $eventRepository->findByAssoAndDate($request->get('id'), $date);
        if (empty($eventList)){
            return $this->json("Event not found")->setStatusCode(404);
        }
        return $this->json($eventList)->setStatusCode(200);
    }

}

