<?php

namespace App\User\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CreateUserController extends AbstractController
{
    public function __construct()
    {
        
    }

    public function __invoke(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CreateUserController.php',
        ]);
    }
}
