<?php

namespace App\Book\Application\Validate;

use Symfony\Component\Validator\Constraints as Assert;

final class BookValidator
{
    #[Assert\NotBlank ]
    public string $isbn;

    #[Assert\NotBlank ]
    public string $author;

    #[Assert\NotBlank ]
    public string $publisher;

    #[Assert\NotBlank ]
    public string $title;

    #[Assert\NotBlank ]
    public string $description;

    /**
     * @param string $isbn
     * @param string $author
     * @param string $publisher
     * @param string $title
     * @param string $description
     */
    public function __construct(
        string $isbn,
        string $author,
        string $publisher,
        string $title,
        string $description
    ) {
        $this->isbn        = $isbn;
        $this->author      = $author;
        $this->publisher   = $publisher;
        $this->title       = $title;
        $this->description = $description;
    }
}
