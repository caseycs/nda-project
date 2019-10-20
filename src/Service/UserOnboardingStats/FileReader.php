<?php
declare(strict_types=1);

namespace App\Service\UserOnboardingStats;

class FileReader
{
    private $filepath;

    public function __construct(string $filepath)
    {
        $this->filepath = $filepath;
    }

    public function read(): array
    {
        $result = [];

        $handle = fopen($this->filepath, 'rb');

        //skipping the first line
        fgetcsv($handle, 1000, ';');

        while (($user = fgetcsv($handle, 1000, ';')) !== FALSE) {
            $result[] = $user;
        }
        fclose($handle);

        return $result;
    }
}
