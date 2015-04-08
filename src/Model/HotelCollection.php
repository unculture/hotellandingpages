<?php

namespace CreativeCherry\HotelLandingPages\Model;


class HotelCollection extends BaseCollection
{

    /**
     * Ensures the collection contains only hotels
     * @param Hotel $hotel
     * @return void
     */
    public function addItem($hotel)
    {
        if (get_class($hotel) !== 'CreativeCherry\HotelLandingPages\Model\Hotel') {
            throw new \InvalidArgumentException;
        }

        if (!$this->hotelAlreadyInCollection($hotel)) {
            parent::addItem($hotel);
        }
    }

    /**
     * Returns true if passed in hotel does not already exist
     * in the Collection, false otherwise
     * @param Hotel $hotel
     * @return bool
     */
    public function hotelAlreadyInCollection(Hotel $hotel)
    {
        return count(array_filter($this->items, function($item) use ($hotel)
        {
            return $item->hotelid === $hotel->hotelid;

        })) !== 0 ? true : false;
    }

    /**
     * Get a hotel from the collection by hotelid
     * @param $hotelid
     * @return Hotel the hotel matching the passed hotelid
     */
    public function getByHotelId($hotelid)
    {

        $filtered = array_filter($this->items, function($item) use ($hotelid)
        {
            return $item->hotelid === $hotelid;
        });

        if (count($filtered) !== 1) {
            throw new \LogicException("There was either no hotel with this hotelid, or the hotel was not unique");
        }

        return current($filtered);
    }

} 