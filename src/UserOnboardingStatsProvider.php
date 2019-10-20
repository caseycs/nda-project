<?php
declare(strict_types=1);

namespace App;

class UserOnboardingStatsProvider
{
    private const FILE = 'data/export.csv';

    private $data;

    public function __construct()
    {
        $handle = fopen(self::FILE, 'rb');
        while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
            $this->data[] = $data;
        }
        fclose($handle);
    }
}
