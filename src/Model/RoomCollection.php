<?php

namespace CreativeCherry\HotelLandingPages\Model;


class RoomCollection extends BaseCollection
{

    /**
     * Ensures the collection contains only Rooms
     * @param Room $room
     * @return void
     */
    public function addItem($room)
    {
        if (get_class($room) !== 'CreativeCherry\HotelLandingPages\Model\Room') {
            throw new \InvalidArgumentException;
        }

        parent::addItem($room);
    }

} 