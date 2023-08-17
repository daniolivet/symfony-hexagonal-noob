<?php

namespace App\Book\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class CreateBookController extends AbstractController
{
    public function __invoke( Request $request ): JsonResponse{
        return $this->json([]);
    }
}
