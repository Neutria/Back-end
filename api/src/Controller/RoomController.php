<?php

namespace App\Controller;

use App\Entity\Room;
use App\Repository\CaptureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RoomController extends AbstractController
{
    public function __construct(
        private CaptureRepository $captureRepository,
        private SerializerInterface $serializer
    ) {}

    #[Route('/api/rooms/last/{roomId}', name: 'room_with_last_capture', methods: ['GET'])]
    public function getRoomWithLastCapture(Room $room): JsonResponse
    {
        $lastCapture = $this->captureRepository->findOneBy(
            ['room' => $room],
            ['createdAt' => 'DESC']
        );

        $data = [
            'id' => $room->getId(),
            'name' => $room->getName(),
            'description' => $room->getDescription(),
            'createdAt' => $room->getCreatedAt(),
            'lastCapture' => $lastCapture ? [
                'id' => $lastCapture->getId(),
                'value' => $lastCapture->getValue(),
                'description' => $lastCapture->getDescription(),
                'createdAt' => $lastCapture->getCreatedAt()
            ] : null
        ];

        return $this->json($data);
    }
}