<?php
declare(strict_types=1);

namespace App;

interface UserOnboardingStatsProviderInterface
{
    /**
     * @return array|UserOnboardingStatsWeek[]
     */
    public function getByWeek(): array;
}
