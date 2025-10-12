<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AcquisitionSystemRepository;
use Doctrine\ORM\Mapping as ORM;
use Carbon\Carbon;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AcquisitionSystemRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['acquisitionSystem:read']],
    denormalizationContext: ['groups' => ['acquisitionSystem:write']]
)]
class AcquisitionSystem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['acquisitionSystem:read', 'capture:room'])]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Groups(['acquisitionSystem:read', 'acquisitionSystem:write', 'capture:room'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'acquisitionSystems')]
    #[Groups(['acquisitionSystem:read', 'acquisitionSystem:write'])]
    private ?Room $room = null;

    #[ORM\Column]
    #[Groups(['acquisitionSystem:read'])]
    private ?\DateTime $createdAt = null;

    public function __construct(){
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

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }
}
