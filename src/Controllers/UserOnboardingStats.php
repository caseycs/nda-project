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
                $item->getWeek()->format('Y-W'),
                $item->getStep1P(),
            ];
        }

        return new \Symfony\Component\HttpFoundation\JsonResponse($result);
    }
}
