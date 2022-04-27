<?php

namespace App\Controller;

use App\Entity\Association;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class PostAssociationController extends AbstractController
{
    public function __invoke(Request $request)
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('file is required');
        }
        $asso = New Association();
        $asso->setName($request->request->get('name'));
        $asso->setFile($uploadedFile);
        return $asso;
    }
}