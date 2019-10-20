<?php
declare(strict_types=1);

namespace App\UserOnboardingStats;

interface ProviderInterface
{
    /**
     * @return array|StepPercentagesPerWeek[]
     */
    public function getByWeek(): array;
}
