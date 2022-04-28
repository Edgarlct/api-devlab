<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    #[Route(
        path: '/api/register',
        name: 'app_register',
        methods: ['POST'])]

    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        $jsonBody = json_decode($request->getContent(), true);
        if ($jsonBody['password'] === $jsonBody['passwordConfirm']){
            $user = new User();
            $user->setFirstName($jsonBody['firstName']);
            $user->setLastName($jsonBody['lastName']);
            $user->setAge($jsonBody['age']);
            $user->setEmail($jsonBody['email']);

            $password = $this->hasher->hashPassword($user, $jsonBody['password']);

            $user->setPassword($password);
            $user->setRoles([]);
            $manager->persist($user);
            $manager->flush();
            return $this->json(true)->setStatusCode(200);
        }
        else{
            return $this->json('Password is not the same ');
        }
    }

}

