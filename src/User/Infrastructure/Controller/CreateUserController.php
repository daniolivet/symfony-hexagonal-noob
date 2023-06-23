<?php

namespace App\User\Infrastructure\Controller;

use App\User\Application\CreateUserUseCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CreateUserController extends AbstractController {

    /**
     * @param CreateUserUseCase $useCase
     */
    public function __construct(
        private readonly CreateUserUseCase $useCase
    ) {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke( Request $request ): JsonResponse {

        $requestData = json_decode( $request->getContent(), true );

        $createUser = ( $this->useCase )( $requestData );

        return $this->json( [
            'response' => $createUser['response'],
            'message'  => $createUser['message'],
        ], $createUser['code'] );
    }

}
