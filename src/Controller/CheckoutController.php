<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Config\Framework\RequestConfig;

class CheckoutController extends AbstractController
{
    public function __construct(private EventRepository $eventRepository,private EntityManagerInterface $entityManager)
    {
    }

    #[Route(
        path: '/api/checkout',
        name: 'app_checkout',
        methods: ['POST'])]



    public function index(Request $request, UserInterface $user, ): Response
    {


        $jsonBody = json_decode($request->getContent(), true);
        $this->treatCommand($jsonBody, $user);


        return $this->json('ok')->setStatusCode(200);
    }


    //trete la commande
    public function treatCommand($requestBody, $user){
        if (isset($requestBody["eventId"])){
            $event = $this->eventRepository->find($requestBody["eventId"]);

            //place gratuite
            if($event->getPrice() == 0 && $event->getPlaceRemaining() > 0){
                $this->createTicket($event, $user);
                return $this->json('place creer')->setStatusCode(200);
            }
            elseif($event->getPrice() > 0 && $event->getPlaceRemaining() > 0) {
                //place paillante
            }
            elseif ($event->getPlaceRemaining() < 0){
                    //plsu de place
            }
            else{
                //erreur inconu
            }
        }
    }

    public function createTicket($event, $user){
        $ticket = new Ticket();
        $ticket
            ->setUser($user)
            ->setEvent($event)
            ->setIsPass(false);

        $this->entityManager->persist($ticket);
        $this->entityManager->flush();
    }

}

