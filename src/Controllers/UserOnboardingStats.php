<?php
declare(strict_types=1);

namespace App\Controllers;

class UserOnboardingStats
{
    /**
     * @var \App\Service\UserOnboardingStats\ProviderInterface
     */
    private $dataProvider;

    public function __construct(\App\Service\UserOnboardingStats\ProviderInterface $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    public function __invoke(): \Symfony\Component\HttpFoundation\Response
    {
        $result = [];
        foreach ($this->dataProvider->getByWeek() as $item) {
            // @todo add data for all steps
            $result[] = [
                $item->getWeek()->format('Y \w\e\e\k W'),
                [
                    round($item->getStep1P() * 100, 2),
                    round($item->getStep2P() * 100, 2),
                    round($item->getStep3P() * 100, 2),
                    round($item->getStep4P() * 100, 2),
                    round($item->getStep5P() * 100, 2),
                    round($item->getStep6P() * 100, 2),
                    round($item->getStep7P() * 100, 2),
                    round($item->getStep8P() * 100, 2),
                ],
            ];
        }

        return new \Symfony\Component\HttpFoundation\JsonResponse($result);
    }
}
