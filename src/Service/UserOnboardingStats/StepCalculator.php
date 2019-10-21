<?php
declare(strict_types=1);

namespace App\Service\UserOnboardingStats;

class StepCalculator
{
    private const CURRENT = [
        20 => 2,
        40 => 3,
        50 => 4,
        70 => 5,
        90 => 6,
        99 => 7,
        100 => 8,
    ];

    private const LEGACY = [
        35 => 2,
        45 => 3,
        55 => 4,
        60 => 4,
        65 => 4,
        95 => 6,
    ];

    public function fromPercentage(string $percentage): int
    {
        if (isset(self::CURRENT[$percentage])) {
            return self::CURRENT[$percentage];
        }

        // looks like there were more onboarding steps before, trying to match them to current
        if (isset(self::LEGACY[$percentage])) {
            return self::LEGACY[$percentage];
        }

        // @todo add custom exception
        throw new \LogicException(sprintf('Complete percentage unknown: "%s"', $percentage));
    }
}
