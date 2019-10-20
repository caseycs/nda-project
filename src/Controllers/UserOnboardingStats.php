<?php
declare(strict_types=1);

namespace App\Controllers;

class UserOnboardingStats
{
    public function __invoke(): \Symfony\Component\HttpFoundation\Response
    {
        // @todo inject via DI
        $dataProvider = new \App\Service\UserOnboardingStats\FileDataProvider(
            new \App\Service\UserOnboardingStats\FileReader(__DIR__ . '/../../data/export.csv'),
            new \App\Service\UserOnboardingStats\StepCalculator
        );

        $result = [];
        foreach ($dataProvider->getByWeek() as $item) {
            // @todo add data for all steps
            $result[] = [
                $item->getWeek()->format('Y-W'),
                $item->getStep1P(),
            ];
        }

        return new \Symfony\Component\HttpFoundation\JsonResponse($result);
    }
}
