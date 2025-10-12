<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CaptureTypeRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CaptureTypeRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['captureType:read']],
    denormalizationContext: ['groups' => ['captureType:write']]
)]
class CaptureType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['captureType:read', 'capture:read', 'capture:room'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['captureType:read', 'captureType:write', 'capture:read', 'capture:room'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['captureType:read', 'captureType:write', 'capture:read', 'capture:room'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['captureType:read'])]
    private ?\DateTime $createdAt = null;

    public function __construct(string $name = null, string $description = null) {
        $this->description = $description;
        $this->name = $name;
        $this->createdAt = Carbon::now();
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
