<?php

namespace App\User\Application\DTO\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class InvalidFormatEmail extends HttpException {

    public function __construct( string $message = '' )
    {
        if( empty( $message ) ) {
            parent::__construct(
                Response::HTTP_BAD_REQUEST,
                "The email does not have a valid format"
            );
            return;
        }

        parent::__construct( 
            Response::HTTP_BAD_REQUEST,
            $message 
        );

    }

}
