<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['light_read'],
            ],
            "security" => "is_granted('ROLE_ADMIN')"
        ],
    ],
    itemOperations: [
        'get'=> [
            "security" => "is_granted('ROLE_ADMIN')"
            ],
        ],
    subresourceOperations: [
        'api_users_orders_get_subresource' => [
            'security' => "is_granted('ROLE_ADMIN')",
        ]
    ],
)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['light_read'])]
    private int $id;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['light_read'])]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: 'float')]
    #[Groups(['light_read'])]
    private float $price;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $weight;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[Groups(['light_read'])]
    private User $user;

//    #[ORM\OneToMany(mappedBy: 'order', targetEntity: AddressType::class)]
//    private $addresses;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $endAt;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $isValidate;

    #[ORM\Column(type: 'string', length: 50)]
    private string $status;

    /*#[ORM\OneToMany(mappedBy: 'purchase', targetEntity: AddressType::class)]
    private $addressTypes;*/

    public function __construct()
    {
//        $this->addresses = new ArrayCollection();
//        $this->addressTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

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
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        $this->addresses->removeElement($address);

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getIsValidate(): ?bool
    {
        return $this->isValidate;
    }

    public function setIsValidate(?bool $isValidate): self
    {
        $this->isValidate = $isValidate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

//    /**
//     * @return Collection|AddressType[]
//     */
//    public function getAddressTypes(): Collection
//    {
//        return $this->addressTypes;
//    }
//
//    public function addAddressType(AddressType $addressType): self
//    {
//        if (!$this->addressTypes->contains($addressType)) {
//            $this->addressTypes[] = $addressType;
//            $addressType->setPurchase($this);
//        }
//
//        return $this;
//    }
//
//    public function removeAddressType(AddressType $addressType): self
//    {
//        if ($this->addressTypes->removeElement($addressType)) {
//            // set the owning side to null (unless already changed)
//            if ($addressType->getPurchase() === $this) {
//                $addressType->setPurchase(null);
//            }
//        }
//
//        return $this;
//    }
}
