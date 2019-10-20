<?php
declare(strict_types=1);

namespace App\UserOnboardingStats;

class StepCalculator
{
    public function fromPercentage(string $percentage): int
    {
        switch ($percentage) {
            case '20':
                return 2;
            case '40':
                return 3;
            case '50':
                return 4;
            case '70':
                return 5;
            case '90':
                return 6;
            case '99':
                return 7;
            case '100':
                return 8;
        }

        // @todo make custom exception
        throw new \LogicException('Complete percentage unknown: ' . $percentage);
    }
}
