<?php
declare(strict_types=1);

use App\Service\UserOnboardingStats\FileDataProvider as FileDataProviderAlias;
use PHPUnit\Framework\TestCase;

final class FileDataProviderTest extends TestCase
{
    public function providerTestGetByWeek(): array
    {
        return [
            'simplest' => [
                [
                    ['3121', '2016-07-19', '40', '0', '0'],
                ],
                [
                    ['2016-07-19', 1, 1, 1, 0, 0, 0, 0, 0],
                ],
            ],
            'two dates same week' => [
                [
                    ['3121', '2019-10-20', '40', '0', '0'],
                    ['3121', '2019-10-19', '40', '0', '0'],
                ],
                [
                    ['2019-10-20', 1, 1, 1, 0, 0, 0, 0, 0],
                ],
            ],
            'two weeks' => [
                [
                    ['3121', '2019-10-20', '40', '0', '0'],
                    ['3121', '2019-10-10', '40', '0', '0'],
                ],
                [
                    ['2019-10-20', 1, 1, 1, 0, 0, 0, 0, 0],
                    ['2019-10-10', 1, 1, 1, 0, 0, 0, 0, 0],
                ],
            ],
            'aggregation among 2' => [
                [
                    ['3121', '2019-10-20', '40', '0', '0'],
                    ['3121', '2019-10-20', '20', '0', '0'],
                ],
                [
                    ['2019-10-20', 1, 1, .5, 0, 0, 0, 0, 0],
                ],
            ],
            'aggregation among 3' => [
                [
                    ['3121', '2019-10-20', '40', '0', '0'],
                    ['3121', '2019-10-20', '20', '0', '0'],
                    ['3121', '2019-10-20', '90', '0', '0'],
                ],
                [
                    ['2019-10-20', 1, 1, 2 / 3, 1 / 3, 1 / 3, 1 / 3, 0, 0],
                ],
            ],
        ];
    }

    /**
     * @dataProvider providerTestGetByWeek
     */
    public function testGetByWeek(array $data, array $expected): void
    {
        $fileReader = \Mockery::mock(\App\Service\UserOnboardingStats\FileReader::class);
        $fileReader->shouldReceive('read')->once()->withNoArgs()->andReturn($data);

        $obj = new FileDataProviderAlias($fileReader, new \App\Service\UserOnboardingStats\StepCalculator);

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
