<?php
require 'vendor/autoload.php';

$a = new App\UserOnboardingStatsProvider(
    new App\UserOnboardingStatsFileReader(__DIR__ . '/data/export.csv')
);
var_dump($a->getByWeek());
