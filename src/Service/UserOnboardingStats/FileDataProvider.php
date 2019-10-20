<?php
declare(strict_types=1);

namespace App\Service\UserOnboardingStats;

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

    private function groupByWeek(array $rawData): array
    {
        // small optimization - this array will be reused
        $steps = array_fill_keys(range(1, 8), 0);

        $result = [];
        foreach ($rawData as $line => $user) {
            $dateUnixtime = strtotime($user[1]);
            $key = date('Y-W', $dateUnixtime);

            if (!isset($result[$key])) {
                // [date, total users count, [users completed step count, ...]]
                $result[$key] = [$dateUnixtime, 0, $steps];
            }

            try {
                $step = $this->stepCalculator->fromPercentage($user[2]);
            } catch (\Exception $e) {
                // @todo application logger should be used
                error_log(
                    sprintf(
                        'fromPercentage failed on line %d (%s) with message "%s", skipping',
                        $line,
                        json_encode($user),
                        $e->getMessage()
                    )
                );
            }

            // total users
            $result[$key][1]++;

            // filling in also all previous steps
            for ($i = 1; $i <= $step; $i ++) {
                $result[$key][2][$i]++;
            }
        }

        return $result;
    }
}
