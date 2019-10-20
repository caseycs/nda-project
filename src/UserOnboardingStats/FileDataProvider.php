<?php
declare(strict_types=1);

namespace App\UserOnboardingStats;

class FileDataProvider implements ProviderInterface
{
    /**
     * @var FileReader
     */
    private $reader;

    /**
     * @var StepCalculator
     */
    private $stepCalculator;

    public function __construct(FileReader $reader, StepCalculator $stepCalculator)
    {
        $this->reader = $reader;
        $this->stepCalculator = $stepCalculator;
    }

    /**
     * @return array|StepPercentagesPerWeek[]
     */
    public function getByWeek(): array
    {
        return $this->aggregatePercentages($this->groupByWeek($this->reader->read()));
    }

    private function groupByWeek(array $rawData): array
    {
        $result = [];
        foreach ($rawData as $user) {
            $dateUnixtime = strtotime($user[1]);
            $key = date('Y-W', $dateUnixtime);

            if (!isset($result[$key])) {
                // [date, count, [completePercentage, ...]]
                $result[$key] = [$dateUnixtime, 0, []];
            }

            try {
                $step = $this->stepCalculator->fromPercentage($user[2]);
            } catch (\LogicException $e) {
                // @todo here should be call to global application logging interface
                trigger_error($e->getMessage(), E_USER_WARNING);
                continue;
            }

            if (!isset($result[$key][2][$step])) {
                $result[$key][2][$step] = 0;
            }
            $result[$key][1]++;
            $result[$key][2][$step]++;
        }
        return $result;
    }

    /**
     * @param array $rawData
     * @return array|StepPercentagesPerWeek
     */
    private function aggregatePercentages(array $rawData): array
    {
        $result = [];

        // small optimization - this array will be reused
        $percentagesBlank = array_fill_keys(range(2, 8), 0);

        foreach ($rawData as $week => $weekData) {
            list ($unixtime, $usersTotal, $steps) = $weekData;

            $percentages = $percentagesBlank;
            foreach ($steps as $step => $usersOnStep) {
                $percentages[$step] = $usersOnStep / $usersTotal;
            }

            $date = \DateTime::createFromFormat('U', (string)$unixtime);

            $result[] = new StepPercentagesPerWeek($date, ...$percentages);
        }
        return $result;
    }
}
