<?php

namespace CreativeCherry\HotelLandingPages\Actions;


use CreativeCherry\HotelLandingPages\Model\HotelCollection;

class CreateReport {

    /**
     * @var string $name The name for this run of the program
     */
    private $name;

    /**
     * @var \CreativeCherry\HotelLandingPages\Model\HotelCollection $hotels The imported hotels
     */
    private $hotels;

    /**
     * @var string $base_url A URL to add before each filename in the report
     */
    private $base_url;

    /**
     * @var string $rootdirectory The directory in which to create output directories
     */
    private $rootdirectory;

    public function __construct($name, HotelCollection $hotels, $base_url, $rootdirectory)
    {
        $this->name = $name;
        $this->hotels = $hotels;
        $this->base_url = $base_url;
        $this->rootdirectory = $rootdirectory;
    }

    /**
     * Creates a CSV file with the
     * @return void
     */
    public function createReport()
    {
        if (!is_dir($this->rootdirectory . DIRECTORY_SEPARATOR .  $this->name)) {
            mkdir($this->rootdirectory . DIRECTORY_SEPARATOR .  $this->name, 0744, true);
        }

        $fp = fopen($this->rootdirectory . DIRECTORY_SEPARATOR .  $this->name . DIRECTORY_SEPARATOR . "report.csv", "w");

        foreach($this->hotels as $hotel) {
            fputcsv($fp, [
                $hotel->hotelid,
                $hotel->hotelname,
                $hotel->getUniqueCode(),
                "http://127.0.0.1:8080/" . $this->name . "/" . $hotel->getUniqueCode() . ".html",
                $this->base_url . "/" . $this->name . "/" . $hotel->getUniqueCode() . ".html"
            ]);
        }

        fclose($fp);
    }

} 