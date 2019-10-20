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
        foreach ($rawData as $line => $user) {
            if ($user[2] === '') {
                // @todo here application logger shoud be used
                trigger_error(sprintf('Percentage missing on line %d, skipping: %s', $line, json_encode($user)));
                continue;
            }

            $dateUnixtime = strtotime($user[1]);
            $key = date('Y-W', $dateUnixtime);

            if (!isset($result[$key])) {
                // [date, count, [completePercentage, ...]]
                $result[$key] = [$dateUnixtime, 0, []];
            }

            $step = $this->stepCalculator->fromPercentage($user[2]);

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
        $percentagesBlank = array_fill_keys(range(1, 8), 0);

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
