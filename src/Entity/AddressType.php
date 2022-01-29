<?php

namespace App\Entity;

use App\Repository\AddressTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressTypeRepository::class)]
class AddressType
{
//    #[ORM\Id]
//    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'addresses')]
//    #[ORM\JoinColumn(nullable: false)]
//    private $purchase;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Address::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $address;

    #[ORM\Column(type: 'string', length: 20)]
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPurchase(): ?Order
    {
        return $this->purchase;
    }

    public function setPurchase(?Order $purchase): self
    {
        $this->purchase = $purchase;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}