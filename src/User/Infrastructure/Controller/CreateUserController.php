<?php

namespace App\User\Infrastructure\Controller;

use App\User\Application\CreateUserUseCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateUserController extends AbstractController {

    public function __construct(
        private readonly CreateUserUseCase $useCase
    ){}

    public function __invoke(Request $request): JsonResponse
    {

        $createUser = ($this->useCase)($request);

        return new JsonResponse([
            'message' => 'Hello from MyController!',
        ]);
    }

}
