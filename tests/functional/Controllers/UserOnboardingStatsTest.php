<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UserOnboardingStatsTest extends TestCase
{
    public function testController(): void
    {
        $result = (new App\Controllers\UserOnboardingStats())();
        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\JsonResponse::class, $result);
        $this->assertJson($result->getContent());
        $this->assertNotEmpty(json_decode($result->getContent(), true));
    }
}
