<?php 

namespace App\User\Domain\Exception;

use RuntimeException;

final class UserExists extends RuntimeException {

    public function __construct( string $message = '' )
    {
        if( empty( $message ) ) {
            parent::__construct(
                'User already exists.'
            );
            return;
        }

        parent::__construct(
            $message 
        );

    }

}
