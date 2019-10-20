<?php
declare(strict_types=1);

require 'vendor/autoload.php';

// no rounting, no 404 handling, almost nothing =)
(new \App\Controllers\UserOnboardingStats())()->send();
