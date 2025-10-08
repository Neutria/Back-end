<?php

namespace App\DataFixtures;

use App\Entity\CaptureType;
use App\Entity\Room;
use App\Entity\Capture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create CaptureTypes
        $captureTypes = [
            ['Temperature', 'Mesure température en °C'],
            ['Humidité', 'Mesure humidité en %'],
            ['CO2', 'Mesure CO2 en ppm'],
            ['Luminosité', 'Mesure luminosité en lux'],
            ['Bruit', 'Mesure bruit en dB'],
        ];

        $captureTypeEntities = [];
        foreach ($captureTypes as [$name, $description]) {
            $captureType = new CaptureType($name, $description);
            $manager->persist($captureType);
            $captureTypeEntities[$name] = $captureType;
        }

        // Create Rooms with their available CaptureTypes
        $rooms = [
            ['Bureau A1', 'Bureau individuel côté sud', ['Temperature', 'Humidité', 'CO2']],
            ['Bureau A2', 'Bureau individuel côté nord', ['Temperature', 'Humidité', 'CO2']],
            ['Open Space', 'Espace partagé 20 pers', ['Temperature', 'Humidité', 'CO2', 'Luminosité', 'Bruit']],
            ['Réunion', 'Salle réunion 8 pers', ['Temperature', 'Humidité', 'CO2', 'Bruit']],
            ['Kitchen', 'Espace détente', ['Temperature', 'Humidité']],
            ['Hall', 'Hall accueil', ['Temperature', 'Luminosité']],
        ];

        $roomEntities = [];
        foreach ($rooms as [$name, $description, $types]) {
            $room = new Room();
            $room->setName($name);
            $room->setDescription($description);
            $room->setCreatedAt(new \DateTime());

            // Add capture types to room
            foreach ($types as $typeName) {
                $room->addCaptureType($captureTypeEntities[$typeName]);
            }

            $manager->persist($room);
            $roomEntities[] = $room;
        }

        // Create sample Captures
        $captureData = [
            'Temperature' => ['values' => ['21.5', '22.8', '20.1', '23.2', '19.7'], 'description' => 'Température'],
            'Humidité' => ['values' => ['45.2', '52.8', '38.1', '61.3', '44.7'], 'description' => 'Humidité'],
            'CO2' => ['values' => ['420', '580', '390', '720', '450'], 'description' => 'CO2'],
            'Luminosité' => ['values' => ['320', '450', '280', '520', '380'], 'description' => 'Luminosité'],
            'Bruit' => ['values' => ['42.5', '38.2', '55.8', '35.1', '48.9'], 'description' => 'Bruit'],
        ];

        foreach ($roomEntities as $room) {
            $hourOffset = 0;

            // Create captures only for types available in the room
            foreach ($room->getCaptureTypes() as $captureType) {
                $typeName = $captureType->getName();
                $data = $captureData[$typeName];

                // Create multiple captures for each type (simulating history)
                for ($i = 0; $i < 3; $i++) {
                    $capture = new Capture();
                    $capture->setValue($data['values'][array_rand($data['values'])]);
                    $capture->setDescription($data['description']);
                    $capture->setRoom($room);
                    $capture->setType($captureType);
                    $capture->setCreatedAt(new \DateTime('-' . $hourOffset . ' hours'));
                    $manager->persist($capture);
                    $hourOffset++;
                }
            }
        }

        $manager->flush();
    }
}
