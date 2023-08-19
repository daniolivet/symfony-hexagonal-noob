<?php

namespace App\Book\Domain\Entity;

use App\Repository\BookRepository;
use App\User\Domain\Entity\User;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class) ]
class Book
{
    #[ORM\Id ]
    #[ORM\GeneratedValue ]
    #[ORM\Column ]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'books') ]
    #[ORM\JoinColumn(name: 'user_uuid', referencedColumnName: 'uuid')]
    private ?User $user;

    #[ORM\Column(length: 17, unique: true) ]
    private string $isbn;

    #[ORM\Column(length: 100) ]
    private string $author;

    #[ORM\Column(length: 100) ]
    private string $publisher;

    #[ORM\Column(length: 100) ]
    private string $title;

    #[ORM\Column(length: 255) ]
    private string $description;

    /**
     * Id getter
     * 
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * User entity getter
     * 
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * User entity setter
     * 
     * @param mixed $user
     * @return self
     */
    public function setUser( ?User $user ): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * ISBN getter
     * 
     * @return string
     */
    public function getIsbn(): string
    {
        return $this->isbn;
    }

    /**
     * ISBN Setter
     * 
     * @param string $isbn
     * @return string
     */
    public function setIsbn( string $isbn ): string
    {
        $this->isbn = $isbn;

        return $this->isbn;
    }

    /**
     * Author getter
     * 
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Author setter
     * 
     * @param string $author
     * @return string
     */
    public function setAuthor( string $author ): string
    {
        $this->author = $author;

        return $this->author;
    }

    /**
     * Publisher getter
     * 
     * @return string
     */
    public function getPublisher(): string
    {
        return $this->publisher;
    }

    /**
     * Publisher setter
     * 
     * @param string $publisher
     * @return string
     */
    public function setPublisher( string $publisher ): string
    {
        $this->publisher = $publisher;

        return $publisher;
    }

    /**
     * Title getter
     * 
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Title setter
     * 
     * @param string $title
     * @return string
     */
    public function setTitle( string $title ): string
    {
        $this->title = $title;

        return $this->title;
    }

    /**
     * Description getter
     * 
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Description setter
     * 
     * @param string $description
     * @return string
     */
    public function setDescription( string $description ): string
    {
        $this->description = $description;

        return $this->description;
    }
}
