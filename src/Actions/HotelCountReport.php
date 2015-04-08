<?php

namespace CreativeCherry\HotelLandingPages\Actions;

/**
 * Class HotelCountReport
 * Counts the hotels in the csv file
 * @package CreativeCherry\HotelLandingPages\Actions
 */
class HotelCountReport
{

    /**
     * @var string the csv data file
     */
    private $datacsv;

    public function __construct($datacsv)
    {
        $this->datacsv = $datacsv;
    }

    /**
     * Creates a CSV file with the
     * @return void
     */
    public function createReport()
    {
        $fp = fopen($this->datacsv, "r");
        // Dump the title row
        fgetcsv($fp);
        $hotels = [];
        while(($line = fgetcsv($fp) ) == true) {
            $hotels[] = array_shift($line); // just take the id
        }
        fclose($fp);
        $hotels = array_unique($hotels);
        echo "\nNumber of Unique Hotel IDs: ";
        echo count($hotels);
        echo "\n";
    }

}
