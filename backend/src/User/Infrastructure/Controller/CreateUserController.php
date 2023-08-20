<?php

namespace App\User\Infrastructure\Controller;

use App\User\Application\CreateUserUseCase;
use App\User\Application\Validate\UserValidator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CreateUserController extends AbstractController {

    /**
     * @param \App\User\Application\CreateUserUseCase $useCase
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(
        private readonly CreateUserUseCase $useCase,
        private readonly ValidatorInterface $validator,
    ) {}

    /**
     * @param  Request        $request
     * @return JsonResponse
     */
    public function __invoke( Request $request ): JsonResponse{
        $requestData = json_decode( $request->getContent(), true );

        $errors = $this->validateRequest( $requestData );

        if ( count( $errors ) > 0 ) {
            return $this->json([
                'response' => false,
                'message'  => 'There is errors in the request.',
                'errors'   => $errors,
            ], Response::HTTP_BAD_REQUEST);
        }

        $createUser = ( $this->useCase )( $requestData );

        return $this->json( [
            'response' => $createUser['response'],
            'message'  => $createUser['message'],
        ], $createUser['code'] );
    }

    /**
     *
     * Validate request data and return errors if exists.
     *
     * @param  array   $data
     * @return array
     */
    private function validateRequest( array $data ): array{
        $requestErrors = [];

        $userValidator = new UserValidator(
            $data['password'] ?? '',
            $data['email'] ?? '',
            $data['name'] ?? '',
            $data['surnames'] ?? ''
        );

        $errors = $this->validator->validate( $userValidator );

        if ( count( $errors ) > 0 ) {
            foreach ( $errors as $error ) {
                $requestErrors[$error->getPropertyPath()] = $error->getMessage();
            }
        }

        return $requestErrors;
    }

}
