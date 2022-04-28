<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final class AssosController extends AbstractController
{
    public function __invoke()
    {
        return $this->json($this->getUser()->getAssociations());
    }
}