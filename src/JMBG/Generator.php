<?php

namespace Tesla\JMBG;

use Exception;

class Generator
{
    /**
     * Generate valid fake JMBG, optionally override default values
     *
     * @param string|null $day
     * @param string|null $month
     * @param string|null $year
     * @param string|null $region
     * @param string $gender
     * @return string
     */
    public function fake(
        ?int $day = 0,
        ?int $month = null,
        ?string $year = null,
        ?string $region = null,
        ?string $gender = null
    ): string {
        $genders = [
            'f' => [
                'from' => 500,
                'to'   => 900,
            ],
            'm' => [
                'from' => 0,
                'to'   => 499,
            ],
        ];

        if (!$gender) {
            $genderKeys = array_keys($genders);
            $gender = $genderKeys[array_rand($genderKeys)];
        }

        $day = str_pad($day, 2, '0', STR_PAD_LEFT);
        $month = str_pad($month ?? rand(1, 12), 2, '0', STR_PAD_LEFT);
        $year = str_pad($year ?? substr(rand(1900, date('Y')), 1), 3, '0', STR_PAD_LEFT);
        $region = str_pad($region ?? rand(0, 96), 2, '0', STR_PAD_LEFT);
        $uniqueId = str_pad(rand($genders[$gender]['from'], $genders[$gender]['to']), 3, '0', STR_PAD_LEFT);

        $jmbg = $day . $month . $year . $region . $uniqueId;

        $checksum = $this->calculateChecksum($jmbg);

        return $jmbg . $checksum;
    }

    /**
     * Calculate checksum for given JMBG without one
     *
     * @param string $value
     * @return int
     */
    private function calculateChecksum(string $value): int
    {
        $pos = (new JMBG)->split($value);

        $k = (7 * ($pos['A'] + $pos['G']))
            + (6 * ($pos['B'] + $pos['H']))
            + (5 * ($pos['C'] + $pos['I']))
            + (4 * ($pos['D'] + $pos['J']))
            + (3 * ($pos['E'] + $pos['K']))
            + (2 * ($pos['F'] + $pos['L']));
        $k = $k % 11;
        $k = 11 - $k;

        $k = $k > 9 ? 0 : $k;

        return $k;
    }
}
