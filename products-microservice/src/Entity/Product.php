<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\ProductRepository;
use App\State\ProductSetCustomerUuidProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['customer:read']],
    denormalizationContext: ['groups' => ['customer:write']],
    operations: [
        new Get(
            uriTemplate: '/my-product',
            security: "is_granted('ROLE_ADMIN') or (object.getCustomerUuid() and object.getCustomerUuid() == user.getUuid())",
            normalizationContext: ['groups' => ['customer:read']]
        ),


        new Get(
            normalizationContext: ['groups' => ['public:read']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['public:read']]
        ),

        new Put(
            security: "is_granted('ROLE_ADMIN') or (object.getCustomerUuid() and object.getCustomerUuid() == user.getUuid())",
            denormalizationContext: ['groups' => ['customer:write']]
        ),
        new Patch(
            security: "is_granted('ROLE_ADMIN') or (object.getCustomerUuid() and object.getCustomerUuid() == user.getUuid())",
            denormalizationContext: ['groups' => ['customer:write']]
        ),
        new GetCollection(
            uriTemplate: '/my-products',
            normalizationContext: ['groups' => ['customer:read']],
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_WEB_SHOPPER')"
        ),
        new Post(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_WEB_SHOPPER')",
            denormalizationContext: ['groups' => ['webshopper:write']],
            processor: ProductSetCustomerUuidProcessor::class
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') or (object.getCustomerUuid() and object.getCustomerUuid() == user.getUuid())",
        )
    ]
)]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["public:read", "customer:read", "customer:write"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(["public:read", "customer:read", "customer:write", "webshopper:write"])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(["public:read", "customer:read", "customer:write", "webshopper:write"])]
    #[ORM\Column]
    private ?int $priceInCents = null;

    #[Groups(["public:read", "customer:read", "customer:write", "webshopper:write"])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Groups(["public:read", "customer:read", "customer:write", "webshopper:write"])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $color = null;

    #[Groups(["public:read", "customer:read", "customer:write", "webshopper:write"])]
    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column(type: 'uuid', nullable: true)]
    private ?string $customerUuid = null; 

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPriceInCents(): ?int
    {
        return $this->priceInCents;
    }

    public function setPriceInCents(int $priceInCents): static
    {
        $this->priceInCents = $priceInCents;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }


    public function getCustomerUuid(): ?string
    {
        return $this->customerUuid;
    }
    
    public function setCustomerUuid(?string $customerUuid): self
    {
        $this->customerUuid = $customerUuid;
        return $this;
    }
}
