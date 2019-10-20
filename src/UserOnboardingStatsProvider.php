<?php
declare(strict_types=1);

namespace App;

class UserOnboardingStatsProvider implements UserOnboardingStatsProviderInterface
{
    /**
     * @var UserOnboardingStatsFileReader
     */
    private $reader;

    public function __construct(UserOnboardingStatsFileReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @return array|UserOnboardingStatsWeek[]
     */
    public function getByWeek(): array
    {
        return $this->aggregatePercentages($this->groupByWeek($this->reader->read()));
    }

    private function calculateStep(string $percentage): int
    {
        switch ($percentage) {
            case '20':
                return 2;
            case '40':
                return 3;
            case '50':
                return 4;
            case '70':
                return 5;
            case '90':
                return 6;
            case '99':
                return 7;
            case '100':
                return 8;
        }
        // @todo make custom exception
        throw new \LogicException('Complete percentage unknown: ' . $percentage);
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
                $step = $this->calculateStep($user[2]);
                if (!isset($result[$key][2][$step])) {
                    $result[$key][2][$step] = 0;
                }
                $result[$key][1]++;
                $result[$key][2][$step]++;
            } catch (\LogicException $e) {
                // @todo here should be call to global application logging interface
                trigger_error($e->getMessage(), E_USER_WARNING);
            }
        }
        return $result;
    }

    /**
     * @param array $rawData
     * @return array|UserOnboardingStatsWeek[]
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

            $result[] = new UserOnboardingStatsWeek($date, ...$percentages);
        }
        return $result;
    }
}
