<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\RoomController;
use App\Repository\RoomRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['capture:room']],
    operations: [
        new GetCollection(),
        new Get(),
        new Get(
            uriTemplate: '/rooms/{id}/last',
            controller: RoomController::class . '::getRoomWithLastCapture'
        ),
        new Post(),
        new Put(),
        new Patch(),
        new Delete()
    ]
)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['capture:room'])]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    #[Groups(['capture:room'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['capture:room'])]
    private ?string $description = null;

    /**
     * @var Collection<int, Capture>
     */
    #[ORM\OneToMany(targetEntity: Capture::class, mappedBy: 'room', cascade: ['remove'])]
    private Collection $captures;

    /**
     * @var Collection<int, CaptureType>
     */
    #[ORM\ManyToMany(targetEntity: CaptureType::class)]
    #[ORM\JoinTable(name: 'room_capture_type')]
    #[Groups(['capture:room'])]
    private Collection $captureTypes;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    public function __construct()
    {
        $this->captures = new ArrayCollection();
        $this->captureTypes = new ArrayCollection();
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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Capture>
     */
    public function getCaptures(): Collection
    {
        return $this->captures;
    }

    public function addCapture(Capture $capture): static
    {
        if (!$this->captures->contains($capture)) {
            $this->captures->add($capture);
            $capture->setRoom($this);
        }

        return $this;
    }

    public function removeCapture(Capture $capture): static
    {
        if ($this->captures->removeElement($capture)) {
            // set the owning side to null (unless already changed)
            if ($capture->getRoom() === $this) {
                $capture->setRoom(null);
            }
        }

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

    /**
     * @return Collection<int, CaptureType>
     */
    public function getCaptureTypes(): Collection
    {
        return $this->captureTypes;
    }

    public function addCaptureType(CaptureType $captureType): static
    {
        if (!$this->captureTypes->contains($captureType)) {
            $this->captureTypes->add($captureType);
        }

        return $this;
    }

    public function removeCaptureType(CaptureType $captureType): static
    {
        $this->captureTypes->removeElement($captureType);

        return $this;
    }
}
