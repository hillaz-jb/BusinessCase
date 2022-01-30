<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ApiResource(
    collectionOperations: [],
    itemOperations: [
        'get' => [
            "security" => "is_granted('ROLE_ADMIN')"
        ],
    ],
    subresourceOperations: [
        'api_users_addresses_get_subresource' => [
            'security' => "is_granted('ROLE_ADMIN')",
        ],
    ],
)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 150)]
    private string $address1;

    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    private ?string $address2;

    #[ORM\Column(type: 'string', length: 5)]
    private string $postalCode;

    #[ORM\Column(type: 'string', length: 255)]
    private string $city;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'addresses')]
    private User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(string $address1): self
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): self
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
