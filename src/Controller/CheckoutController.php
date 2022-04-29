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
    public function __construct(private EventRepository $eventRepository, private EntityManagerInterface $entityManager)
    {
    }

    #[Route(
        path: '/api/checkout',
        name: 'app_checkout',
        methods: ['POST'])]
    public function index(Request $request, UserInterface $user): Response
    {


        $jsonBody = json_decode($request->getContent(), true);

        $treatReturn = $this->treatCommand($jsonBody, $user);

        return $this->json($treatReturn)->setStatusCode(200);
    }


    //trete la commande
    public function treatCommand($requestBody, $user)
    {
        if (isset($requestBody["eventId"])) {
            $event = $this->eventRepository->find($requestBody["eventId"]);

            //place gratuite
            if ($event->getPrice() === 0 && $event->getPlaceRemaining() > 0) {
                $this->createTicket($event, $user);
                return ["success" => true, "needBilling" => "false"];
            }
            //place paillante
            elseif ($event->getPrice() > 0 && $event->getPlaceRemaining() > 0) {
                $this->createTicket($event, $user);
                return ["success" => false, "needBilling" => "true", "stripe" => $this->createCheckout()];
            }

            elseif ($event->getPlaceRemaining() < 0) {
                //plsu de place
            }

            else {
                //erreur inconu
            }
        }
    }
    
    public function createTicket($event, $user)
    {
        $ticket = new Ticket();
        $ticket
            ->setUser($user)
            ->setEvent($event)
            ->setIsPass(false);

        $this->entityManager->persist($ticket);
        $this->entityManager->flush();
    }

    public function createCheckout()
    {

        \Stripe\Stripe::setApiKey('sk_test_51KskwVJ36F2VD1N9Eb9d6GtbhpTdqug6WNJL2caczsoHxucIfFHJm6WQPECvqfndFPZXfjUDwSerd4aWzpe85vTn00Oef6lHkm');

        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => 1099,
            'currency' => 'eur',
            'payment_method_types' => ['bancontact', 'card', 'ideal', 'klarna', 'sepa_debit', 'sofort']]);

          return [
              'paymentIntent' => $paymentIntent->client_secret,
              'paymentId' => $paymentIntent->id,
              'publishableKey' => 'pk_test_51KskwVJ36F2VD1N9CFZJl9lajxZhG0OKoWTGbWhJa3D4fyWt5S6izP4khu9QjgALQruezdDJPcJvYegCJB1LLmyO00x2qUhOQe'
          ];



    }

}

