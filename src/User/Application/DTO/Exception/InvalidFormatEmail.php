<?php

namespace App\User\Application\DTO\Exception;

use RuntimeException;

final class InvalidFormatEmail extends RuntimeException {

    public function __construct( string $message = '' )
    {
        if( empty( $message ) ) {
            parent::__construct(
                "The email does not have a valid format"
            );
            return;
        }

        parent::__construct(
            $message 
        );

    }

}
