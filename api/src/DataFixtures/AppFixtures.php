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
            $captureTypeEntities[] = $captureType;
        }

        // Create Rooms
        $rooms = [
            ['Bureau A1', 'Bureau individuel côté sud'],
            ['Bureau A2', 'Bureau individuel côté nord'],
            ['Open Space', 'Espace partagé 20 pers'],
            ['Réunion', 'Salle réunion 8 pers'],
            ['Kitchen', 'Espace détente'],
            ['Hall', 'Hall accueil'],
        ];

        $roomEntities = [];
        foreach ($rooms as [$name, $description]) {
            $room = new Room();
            $room->setName($name);
            $room->setDescription($description);
            $room->setCreatedAt(new \DateTime());
            $manager->persist($room);
            $roomEntities[] = $room;
        }

        // Create sample Captures
        $temperatureValues = ['21.5', '22.8', '20.1', '23.2', '19.7'];
        $humidityValues = ['45.2', '52.8', '38.1', '61.3', '44.7'];
        $co2Values = ['420', '580', '390', '720', '450'];
        $luminosityValues = ['320', '450', '280', '520', '380'];
        $noiseValues = ['42.5', '38.2', '55.8', '35.1', '48.9'];

        foreach ($roomEntities as $room) {
            // Temperature capture
            $capture = new Capture();
            $capture->setValue($temperatureValues[array_rand($temperatureValues)]);
            $capture->setDescription('Température');
            $capture->setRoom($room);
            $capture->setCreatedAt(new \DateTime());
            $manager->persist($capture);

            // Humidity capture
            $capture = new Capture();
            $capture->setValue($humidityValues[array_rand($humidityValues)]);
            $capture->setDescription('Humidité');
            $capture->setRoom($room);
            $capture->setCreatedAt(new \DateTime('-1 hour'));
            $manager->persist($capture);

            // CO2 capture
            $capture = new Capture();
            $capture->setValue($co2Values[array_rand($co2Values)]);
            $capture->setDescription('CO2');
            $capture->setRoom($room);
            $capture->setCreatedAt(new \DateTime('-2 hours'));
            $manager->persist($capture);

            // Luminosity capture
            $capture = new Capture();
            $capture->setValue($luminosityValues[array_rand($luminosityValues)]);
            $capture->setDescription('Luminosité');
            $capture->setRoom($room);
            $capture->setCreatedAt(new \DateTime('-3 hours'));
            $manager->persist($capture);

            // Noise capture
            $capture = new Capture();
            $capture->setValue($noiseValues[array_rand($noiseValues)]);
            $capture->setDescription('Bruit');
            $capture->setRoom($room);
            $capture->setCreatedAt(new \DateTime('-4 hours'));
            $manager->persist($capture);
        }

        $manager->flush();
    }
}
