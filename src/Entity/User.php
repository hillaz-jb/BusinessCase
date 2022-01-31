<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(
    fields: ['email'],
    message: 'register.constraints.unique',
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['light_read'],
            ],
            "security" => "is_granted('ROLE_ADMIN')"
        ],
        'post' => [
            'denormalization_context' => [
                'groups' => ['light_write'],
            ],
            "security" => "is_granted('ROLE_SUPER_ADMIN')"
        ]
    ],
    itemOperations: [
        'get' => ["security" => "is_granted('ROLE_SUPER_ADMIN')"],
        'put' => ["security" => "is_granted('ROLE_SUPER_ADMIN')"],
        'delete' => ["security" => "is_granted('ROLE_SUPER_ADMIN')"],
    ],
    subresourceOperations: [
        'api_users_addresses_get_subresource' => [
            'security' => "is_granted('ROLE_ADMIN')",
        ],
        'api_users_orders_get_subresource' => [
            'security' => "is_granted('ROLE_ADMIN')",
        ]
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'partial', 'lastName' => 'partial'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['light_read'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['light_read', 'light_write'])]
    #[Assert\NotBlank, Assert\Email(
        message: 'register.constraints.email.message',
    ), Assert\Length(
        max: '180',
        maxMessage: 'register.constraints.email.maxMessage',
    )]
    private string $email;

    #[ORM\Column(type: 'json')]
    #[Groups(['light_write'])]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    #[Groups(['light_write'])]
    #[Assert\Length(
        min: '8',
        max: '255',
        minMessage: 'register.constraints.password.minMessage',
        maxMessage: 'register.constraints.password.maxMessage',
    )]
    public string $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['light_read', 'light_write'])]
    #[Assert\NotBlank(
        message: 'register.constraints.not_blank',
    )]
    private string $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['light_read', 'light_write'])]
    #[Assert\NotBlank(
        message: 'register.constraints.not_blank',
    )]
    private string $lastName;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Order::class)]
    #[ApiSubresource]
    private Collection $orders;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Address::class)]
    #[ApiSubresource]
    private Collection $addresses;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['light_read', 'light_write'])]
    #[Assert\Type(DateTimeInterface::class), Assert\Range(
        notInRangeMessage: 'register.constraints.birthdate.range',
        min: '-120 years',
        max: '-18 years',
    )]
    private DateTimeInterface $birthDate;

    #[Pure] public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->addresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

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
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    public function getBirthDate(): ?DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }
}
