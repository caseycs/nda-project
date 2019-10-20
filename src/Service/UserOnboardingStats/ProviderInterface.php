<?php
declare(strict_types=1);

namespace App\Service\UserOnboardingStats;

interface ProviderInterface
{
    /**
     * @return array|StepPercentagesPerWeek[]
     */
    public function getByWeek(): array;
}
