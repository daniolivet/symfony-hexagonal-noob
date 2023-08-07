<?php

namespace App\User\Domain\Exception;

use RuntimeException;

final class InvalidFormatPassword extends RuntimeException {

    public function __construct( string $message = '' )
    {
        if( empty( $message ) ) {
            parent::__construct(
                'The password must have 8 characters, lowercase, uppercase, numbers and special characters.'
            );
            return;
        }

        parent::__construct(
            $message 
        );

    }

}
