<?php

namespace CreativeCherry\HotelLandingPages\Model;

class Hotel extends BaseModel
{
    /**
     * @var RoomCollection Contains rooms belonging to this hotel
     */
    private $rooms;

    public function __construct($property_names, $property_values)
    {
        parent::__construct($property_names, $property_values);
        $this->rooms = new RoomCollection();
    }

    /**
     * Add a room to this hotel
     * @param Room $room
     */
    public function addRoom(Room $room)
    {
        $this->rooms->addItem($room);
    }

    /**
     * Get the rooms belonging to this hotel
     * @return RoomCollection The rooms belonging to this hotel
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Gets the unique hash for this hotel
     * @return string an md5 hash
     * @throws I
     */
    public function getUniqueCode()
    {
        if (($this->hotelid === null) && ($this->hotelname === null)) {
            throw new \LogicException("There should be two properties on hotel in order to generate a Unique Code - hotelid and hotelname");
        }

        return md5($this->hotelname . $this->hotelid . "2jdkjkj3j4499");
    }

} 