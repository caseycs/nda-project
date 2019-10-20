<?php
declare(strict_types=1);

require 'vendor/autoload.php';

$dataProvider = new \App\UserOnboardingStats\FileDataProvider(
    new \App\UserOnboardingStats\FileReader(__DIR__ . '/data/export.csv'),
    new \App\UserOnboardingStats\StepCalculator
);

$result = [];
foreach ($dataProvider->getByWeek() as $item) {
    $result[] = [
        $item->getWeek()->format('Y-W'),
        $item->getStep1P(),
    ];
}

$result = new \Symfony\Component\HttpFoundation\JsonResponse($result);
$result->send();
