<?php
declare(strict_types=1);

use App\UserOnboardingStats\FileDataProvider as FileDataProviderAlias;
use PHPUnit\Framework\TestCase;

final class FileDataProviderTest extends TestCase
{
    public function providerTestGetByWeek()
    {
        return [
            [
                [['3121', '2016-07-19', '40', '0', '0']],
                [['2016-07-19', 0, 0, 1, 0, 0, 0, 0, 0]],
            ],
        ];
    }

    /**
     * @dataProvider providerTestGetByWeek
     */
    public function testGetByWeek(array $data, array $expected)
    {
        $fileReader = \Mockery::mock(\App\UserOnboardingStats\FileReader::class);
        $fileReader->shouldReceive('read')->once()->withNoArgs()->andReturn($data);

        $obj = new FileDataProviderAlias($fileReader, new \App\UserOnboardingStats\StepCalculator);

        $result = [];
        foreach ($obj->getByWeek() as $byWeek) {
            $result[] = [
                $byWeek->getWeek()->format('Y-m-d'),
                $byWeek->getStep1P(),
                $byWeek->getStep2P(),
                $byWeek->getStep3P(),
                $byWeek->getStep4P(),
                $byWeek->getStep5P(),
                $byWeek->getStep6P(),
                $byWeek->getStep7P(),
                $byWeek->getStep8P(),
            ];
        }

        $this->assertEquals($expected, $result);
    }
}
