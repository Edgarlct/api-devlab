<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints\Date;

#[AsController]
final class PostEventController extends AbstractController
{
    public function __invoke(Request $request)
    {
        $uploadedFile = $request->files->get('file');
        if (!$this->getUser() or !$this->getUser()->getAssociationOffice()) {
            throw new BadRequestHttpException('No access');
        }
//        return $this->json($uploadedFile);
        if (!$uploadedFile) {
            throw new BadRequestHttpException('file is required');
        }

        $date = new \DateTimeImmutable();
        $date->setTimestamp(strtotime($request->request->get('date')));

        $event = New Event();
        $event->setName($request->request->get('name'));
        $event->setPrice($request->request->get('price'));
        $event->setAssociation($this->getUser()->getassociationOffice());
        $event->setDate( $date);
        $event->setFile($uploadedFile);
        $event->setPlaceNumber($request->request->get('place'));
        $event->setPlaceRemaining($request->request->get('place'));
        $event->setNamePlace($request->request->get('namePlace'));
        $event->setDescription($request->request->get('description'));
        $event->setAdresse($request->request->get('adresse'));
        return $event;
    }
}