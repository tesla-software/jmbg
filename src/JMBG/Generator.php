<?php

namespace Tesla\JMBG;

use Exception;

class Generator
{
    /**
     * Generate valid fake JMBG, optionally
     * override default values
     *
     * @param string|null $day
     * @param string|null $month
     * @param string|null $year
     * @param string|null $region
     * @param string $gender
     * @return string
     */
    public function fake(
        ?string $day = null,
        ?string $month = null,
        ?string $year = null,
        ?string $region = null,
        string $gender = 'f'
    ): string {
        $gender = strtolower($gender);
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

        if (!array_key_exists($gender, $genders)) {
            throw new Exception('Invalid gender definition');
        }

        $day = $this->padDigits($day ?? '00', 2, 2);
        $month = $this->padDigits($month ?? rand(1, 12), 2, 2);
        $year = $this->padDigits($year ?? rand(1950, (int)date('Y') - 18), 4, 3);
        $region = $this->padDigits($region ?? rand(0, 96), 2, 2);
        $uniqueId = rand($genders[$gender]['from'], $genders[$gender]['to']);

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

    /**
     * Format digits
     *
     * @param string $value
     * @param int|null $minDigits
     * @param int|null $maxDigits
     * @return string
     */
    private function padDigits(string $value, ?int $minDigits = 2, ?int $maxDigits = 2): string
    {
        $value = sprintf('%0' . $minDigits . 'd', $value);

        return substr($value, $maxDigits * -1);

    }
}
