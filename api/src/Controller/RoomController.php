<?php

namespace App\Controller;

use ApiPlatform\Metadata\Exception\ResourceClassNotFoundException;
use App\Entity\Room;
use App\Repository\CaptureRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
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

    public function getRoomWithLastCapture(Room $room): JsonResponse
    {
        $lastCapturesByType = [];

        // Get the last capture for each type available in this room
        foreach ($room->getCaptureTypes() as $captureType) {
            $lastCapture = $this->captureRepository->findOneBy(
                ['room' => $room, 'type' => $captureType],
                ['createdAt' => 'DESC']
            );

            if ($lastCapture) {
                $lastCapturesByType[] = [
                    'type' => [
                        'id' => $captureType->getId(),
                        'name' => $captureType->getName(),
                        'description' => $captureType->getDescription()
                    ],
                    'capture' => [
                        'id' => $lastCapture->getId(),
                        'value' => $lastCapture->getValue(),
                        'description' => $lastCapture->getDescription(),
                        'createdAt' => $lastCapture->getCreatedAt()->format('c'),
                        'dateCaptured' => $lastCapture->getDateCaptured()?->format('c')
                    ]
                ];
            }
        }

        $data = [
            'id' => $room->getId(),
            'name' => $room->getName(),
            'description' => $room->getDescription(),
            'createdAt' => $room->getCreatedAt()->format('Y-m-d H:i:s'),
            'lastCapturesByType' => $lastCapturesByType
        ];

        return $this->json($data);
    }
}