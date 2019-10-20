<?php
declare(strict_types=1);

require 'vendor/autoload.php';

// no rounting, no 404 handling, almost nothing =)
// @todo inject via DI
(new \App\Controllers\UserOnboardingStats(
    new \App\Service\UserOnboardingStats\FileDataProvider(
        new \App\Service\UserOnboardingStats\FileReader(__DIR__ . '/data/export.csv'),
        new \App\Service\UserOnboardingStats\StepCalculator
    )
))()->send();
