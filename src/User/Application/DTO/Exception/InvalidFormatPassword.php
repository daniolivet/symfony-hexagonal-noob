<?php

namespace App\User\Application\DTO\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class InvalidFormatPassword extends HttpException {

    public function __construct( string $message = '' )
    {
        if( empty( $message ) ) {
            parent::__construct(
                Response::HTTP_BAD_REQUEST,
                'The password must have 8 characters, lowercase, uppercase, numbers and special characters.'
            );
            return;
        }

        parent::__construct( 
            Response::HTTP_BAD_REQUEST,
            $message 
        );

    }

}
