<?php

namespace Tesla\JMBG;

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
        ?string $day = '0',
        ?string $month = null,
        ?string $year = null,
        ?string $region = null,
        ?string $gender = null
    ): string {
        $day = $day ?? '0';
        $month = $month ?? (string) rand(1, 12);
        $year = $year ?? substr((string) rand(1900, (int) date('Y')), 1);
        $region = $region ?? (string) rand(0, 96);

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

        if (is_null($gender)) {
            $genderKeys = array_keys($genders);
            $gender = $genderKeys[array_rand($genderKeys)];
            $gender = (string) rand($genders[$gender]['from'], $genders[$gender]['to']);
        }

        $day = str_pad($day, 2, '0', STR_PAD_LEFT);
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $year = str_pad($year, 3, '0', STR_PAD_LEFT);
        $region = str_pad($region, 2, '0', STR_PAD_LEFT);
        $uniqueId = str_pad($gender, 3, '0', STR_PAD_LEFT);

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
        $pos = (new JMBG())->split($value);

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
