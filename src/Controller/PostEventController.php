<?php

namespace App\Controller;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
        $event = New Event();
        $event->setName($request->request->get('name'));
        $event->setPrice($request->request->get('price'));
        $event->setAssociation($this->getUser()->getassociationOffice());
        $event->setDate(date_create_from_format('j m Y', $request->request->get('date')));
        $event->setFile($uploadedFile);
        $event->setPlaceNumber($request->request->get('place'));
        $event->setPlaceRemaining($request->request->get('place'));
        return $event;
    }
}