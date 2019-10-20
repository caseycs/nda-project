<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UserOnboardingStatsTest extends TestCase
{
    public function testController(): void
    {
        // @todo inject via DI
        $result = (new \App\Controllers\UserOnboardingStats(
            new \App\Service\UserOnboardingStats\FileDataProvider(
                new \App\Service\UserOnboardingStats\FileReader(__DIR__ . '/../../../data/export.csv'),
                new \App\Service\UserOnboardingStats\StepCalculator
            )
        ))();

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\JsonResponse::class, $result);
        $this->assertJson($result->getContent());
        $this->assertNotEmpty(json_decode($result->getContent(), true));
    }
}
