<?php

namespace CreativeCherry\HotelLandingPages\Actions;


use CreativeCherry\HotelLandingPages\Model\HotelCollection;
use CreativeCherry\HotelLandingPages\Model\Hotel;
use CreativeCherry\HotelLandingPages\Model\Room;

class ImportHotels {

    /**
     * @var The filename of the csv file containing the data
     */
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }


    /**
     * Import the hotels
     * @return HotelCollection A collection of hotels found from the files
     */
    public function import()
    {
        $hotels = new HotelCollection();

        // The assumption is that the first line of the CSV
        // file contains the property names
        $fp = fopen($this->filename, "r");
        $properties = fgetcsv($fp);

        // Shift off the hotelid and hotelname fields
        array_shift($properties);
        array_shift($properties);
        while (($line = fgetcsv($fp)) !== false) {

            // Shift off the hotelid and hotelname values
            $hotelid = array_shift($line);
            $hotelname = array_shift($line);

            $hotels->addItem(new Hotel([
                "hotelid",
                "hotelname"
            ], [
                $hotelid,
                $hotelname
            ]));

            // Get the unique hotel
            $hotel = $hotels->getByHotelId($hotelid);

            // Add this line's room to the hotel
            $hotel->addRoom(new Room($properties, $line));
        }

        fclose($fp);

        return $hotels;
    }
} 