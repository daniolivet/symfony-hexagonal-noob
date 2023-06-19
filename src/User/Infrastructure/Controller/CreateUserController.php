<?php

namespace App\User\Infrastructure\Controller;

use App\User\Application\CreateUserUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CreateUserController extends AbstractController {

    public function __construct(
        private readonly CreateUserUseCase $useCase
    ){}

    public function __invoke(Request $request): JsonResponse
    {

        $user = ($this->useCase)();

        return new JsonResponse([
            'message' => 'Hello from MyController!',
        ]);
    }

}
