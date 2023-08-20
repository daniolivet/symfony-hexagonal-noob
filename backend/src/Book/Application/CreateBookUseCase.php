<?php

namespace App\Book\Application;

final class CreateBookUseCase {

    public function __invoke( array $requestData ): array {

        $book = $this->createBook($requestData);
    }

}
