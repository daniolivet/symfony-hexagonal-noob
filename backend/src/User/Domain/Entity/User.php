<?php

namespace App\User\Domain\Entity;

use App\Book\Domain\Entity\Book;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\User\Application\Events\UserCreatedEvent;
use Symfony\Contracts\EventDispatcher\Event;
use App\User\Domain\Entity\ValueObjects\Password;
use App\User\Domain\Entity\ValueObjects\Email;
use App\User\Domain\Entity\ValueObjects\Name;
use App\User\Domain\Entity\ValueObjects\Surnames;
use App\User\Domain\Repository\UserRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: UserRepository::class) ]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    protected array $domainEvents = [];

    #[ORM\Id ]
    #[ORM\Column(length: 180, unique: true) ]
    private string $uuid;

    #[ORM\Column ]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column ]
    private ?string $password = null;

    #[ORM\Column(unique: true) ]
    private string $email;

    #[ORM\Column(length: 50) ]
    private string $name;

    #[ORM\Column(length: 100) ]
    private string $surnames;

    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: 'user') ]
    private Collection $books;

    public function __construct(
        UuidV4 $uuid,
        Password $password,
        Email $email,
        Name $name,
        Surnames $surnames
    ) {
        $this->setUuid( $uuid );
        $this->setPassword( $password->getValue() );
        $this->setEmail( $email->getValue() );
        $this->setName( $name->getValue() );
        $this->setSurnames( $surnames->getValue() );

        $this->books = new ArrayCollection();
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid( string $uuid ): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * 
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->uuid;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->uuid;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique( $roles );
    }

    public function setRoles( array $roles ): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword( string $password ): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail( string $email ): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName( string $name ): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurnames(): string
    {
        return $this->surnames;
    }

    public function setSurnames( string $surnames ): self
    {
        $this->surnames = $surnames;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @param string   $uuid
     * @param Password $password
     * @param Email    $email
     * @param Name     $name
     * @param Surnames $surnames
     */
    public static function create(
        UuidV4 $uuid,
        Password $password,
        Email $email,
        Name $name,
        Surnames $surnames
    ) {
        $user = new self(
            $uuid,
            $password,
            $email,
            $name,
            $surnames
        );

        $user->recordDomainEvent(
            new UserCreatedEvent(
                $user->getEmail()
            )
        );

        return $user;
    }

    public function recordDomainEvent( Event $event ): self
    {
        $this->domainEvents[] = $event;
        return $this;
    }
    public function pullDomainEvents(): array
    {
        $domainEvents       = $this->domainEvents;
        $this->domainEvents = [];
        return $domainEvents;
    }
}
