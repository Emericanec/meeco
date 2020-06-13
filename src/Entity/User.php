<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * * @ORM\Table(
 *     name="user",
 *     indexes={
 *          @ORM\Index(name="idx_confirm_hash", columns={"personal_hash"}),
 *     },
 *)
 */
class User implements UserInterface
{
    private const SALT = 'ee11cbb19052e40b07aac0ca060c23ee';

    public const ROLE_USER = 'ROLE_USER';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @var string[]
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private string $apiToken = '';

    /**
      * @var boolean
      * @ORM\Column(type="boolean")
      */
    private bool $isActivated = false;

    /**
     * @var Collection<int, Integration>
     * @ORM\OneToMany(targetEntity="Integration", mappedBy="user")
     */
    private Collection $integrations;

    /**
     * @var string hash for activate account
     * @ORM\Column(type="string", length=40)
     */
    private ?string $personalHash = '';

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getIsActivated(): bool
    {
        return $this->isActivated;
    }

    public function setIsActivated(bool $isActivated): self
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    public function getPersonalHash(): string
   {
       return $this->personalHash;
   }

   public function setPersonalHash($valueForHash): self
   {
       $this->personalHash = sha1($valueForHash);

       return $this;
   }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getApiToken(): string
    {
        return $this->apiToken;
    }

    public function generateNewApiToken(): string
    {
        $this->apiToken = Uuid::uuid4()->toString();

        return $this->getApiToken();
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = self::ROLE_USER;

        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): string
    {
        return self::SALT;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Integration[]
     */
    public function getIntegrations(): array
    {
        return $this->integrations->getValues();
    }


}
