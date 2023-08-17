<?php

namespace App\User\Infrastructure\Controller;

use App\User\Application\LoginUserUseCase;
use App\User\Application\Validate\LoginUserValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginUserController extends AbstractController
{
    public function __construct(
        private readonly LoginUserUseCase $useCase,
        private readonly ValidatorInterface $validator
    ) {}

    public function __invoke( Request $request ) {
        $requestData = json_decode( $request->getContent(), true);

        $errors = $this->validateRequest( $requestData );

        if ( count( $errors ) > 0 ) {
            return $this->json([
                'response' => false,
                'message'  => 'There is errors in the request.',
                'errors'   => $errors,
            ], Response::HTTP_BAD_REQUEST);
        }

        $loginUser = ( $this->useCase )( $requestData );

        $response = [
            'response' => $loginUser['response'],
            'message'  => $loginUser['message']
        ];

        if( isset($loginUser['token']) ) {
            $response['token'] = $loginUser['token'];
        }

        return $this->json( $response, $loginUser['code']);
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

        $userValidator = new LoginUserValidator(
            $data['email'],
            $data['password']
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