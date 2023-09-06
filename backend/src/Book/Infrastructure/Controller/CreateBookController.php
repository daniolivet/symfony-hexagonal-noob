<?php

namespace App\Book\Infrastructure\Controller;

use App\Book\Application\CreateBookUseCase;
use App\Book\Application\Validate\BookValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateBookController extends AbstractController
{

    /**
     * @param \App\Book\Application\CreateBookUseCase $useCase
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(
        private readonly CreateBookUseCase $useCase,
        private readonly ValidatorInterface $validator
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function __invoke( Request $request ): JsonResponse
    {
        $requestData = json_decode( $request->getContent(), true );

        $errors = $this->validateRequest( $requestData );

        if ( count( $errors ) > 0 ) {
            return $this->json( [ 
                'response' => false,
                'message'  => 'There is errors in the request.',
                'errors'   => $errors,
            ], Response::HTTP_BAD_REQUEST );
        }

        $createBook = ( $this->useCase )( $requestData );

        return $this->json( [ 
            'response' => $createBook['response'],
            'message'  => $createBook['message'],
        ], $createBook['code'] );
    }

    /**
     *
     * Validate request data and return errors if exists.
     *
     * @param  array   $data
     * @return array
     */
    private function validateRequest( array $data ): array
    {
        $requestErrors = [];

        $userValidator = new BookValidator(
            $data['isbn'],
            $data['author'],
            $data['publisher'],
            $data['title'],
            $data['description']
        );

        $errors = $this->validator->validate( $userValidator );

        if ( count( $errors ) > 0 ) {
            foreach ( $errors as $error ) {
                $requestErrors[ $error->getPropertyPath()] = $error->getMessage();
            }
        }

        return $requestErrors;
    }
}
