<?php
declare(strict_types=1);

require 'vendor/autoload.php';

$a = new \App\UserOnboardingStats\FileDataProvider(
    new \App\UserOnboardingStats\FileReader(__DIR__ . '/data/export.csv'),
    new \App\UserOnboardingStats\StepCalculator
);
var_dump($a->getByWeek());
