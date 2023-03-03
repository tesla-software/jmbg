<?php

namespace Tesla\JMBG;

use DateTime;

final class JMBG
{
    /**
     * @var string|null
     */
    private $jmbg;

    /**
     * Construct JMBG
     *
     * @param string|null $jmbg
     */
    public function __construct(?string $jmbg = null)
    {
        $this->jmbg = $jmbg;
    }

    /**
     * Validate JMBG
     *
     * @return boolean
     */
    public function isValid(): bool
    {
        // Length must be at least 13 digits long
        if (is_null($this->jmbg) || strlen($this->jmbg) != 13 || !ctype_digit($this->jmbg)) {
            return false;
        }

        $pos = $this->split($this->jmbg);

        // Handle special region case for foreigners
        // 66 - Temporary residence
        // 06 - Permanent residence
        // Foreigners with temporary residence cannot be validated via checksum
        // ref: http://www.ubs-asb.com/Portals/0/Casopis/2008/3_4/B03-04-2008-PO.pdf
        if ($pos['H'] == 6 && $pos['I'] == 6) {
            return true;
        }

        // Calculate control number
        $checksum = 11 - (
                7 * ($pos['A'] + $pos['G']) +
                6 * ($pos['B'] + $pos['H']) +
                5 * ($pos['C'] + $pos['I']) +
                4 * ($pos['D'] + $pos['J']) +
                3 * ($pos['E'] + $pos['K']) +
                2 * ($pos['F'] + $pos['L'])) % 11;

        if ($checksum > 9) {
            $checksum = 0;
        }

        return $checksum == $pos['M'];
    }

    /**
     * Extract gender information
     *
     * @return string
     */
    public function getGender(): string
    {
        $pos = $this->split($this->jmbg);
        $gender = (int)($pos['J'] . $pos['K'] . $pos['L']);

        return ($gender < 500) ? 'm' : 'f';
    }

    /**
     * Get date of birth
     *
     * @return DateTime
     * @throws \Exception
     */
    public function getBirthday(): DateTime
    {
        $pos = $this->split($this->jmbg);

        $day = $pos['A'] . $pos['B'];
        $month = $pos['C'] . $pos['D'];
        $year = $pos['E'] . $pos['F'] . $pos['G'];

        if ((int)$year > 900) {
            $year = '1' . $year;
        } else {
            $year = '2' . $year;
        }

        return new DateTime(implode('-', [(int)$year, (int)$month, (int)$day]));
    }

    /**
     * Split number into variables for easier use in formula calculations
     *
     * AB CD EFG HI JKL M
     *
     * AB – Day of birth
     * CD – Birth month
     * EFG – Last 3 numbers of birth year
     * HI – Birth region
     * JKL – Unique gender description number
     * M - Checksum
     *
     * @param  string $jmbg
     * @return array<mixed>
     */
    public function split(?string $jmbg): array
    {
        if (is_null($jmbg)) {
            return [];
        }

        $pos = str_split($jmbg);

        $split = [
            'A' => $pos[0],
            'B' => $pos[1],
            'C' => $pos[2],
            'D' => $pos[3],
            'E' => $pos[4],
            'F' => $pos[5],
            'G' => $pos[6],
            'H' => $pos[7],
            'I' => $pos[8],
            'J' => $pos[9],
            'K' => $pos[10],
            'L' => $pos[11],
        ];

        if (isset($pos[12])) {
            $split['M'] = $pos[12];
        }

        return $split;
    }

    /**
     * Static call
     *
     * @param string|null $jmbg
     * @return JMBG
     */
    public static function for(?string $jmbg = null): JMBG
    {
        return new static($jmbg);
    }
}
