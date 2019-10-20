<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class StepCalculatorTest extends TestCase
{
    public function providerFromPercentageCurrent(): array
    {
        return [
            [20, 2],
            [40, 3],
            [70, 5],
            [90, 6],
            [99, 7],
            [100, 8],
        ];
    }

    /**
     * @dataProvider providerFromPercentageCurrent
     */
    public function testFromPercentageCurrent(string $percentage, int $expected): void
    {
        $obj = new \App\UserOnboardingStats\StepCalculator;
        $this->assertSame($expected, $obj->fromPercentage($percentage));
    }

    /**
     * @expectedException \LogicException
     */
    public function testFromPercentageInvalid(): void
    {
        $obj = new \App\UserOnboardingStats\StepCalculator;
        $obj->fromPercentage('');
    }
}
