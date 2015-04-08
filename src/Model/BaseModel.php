<?php

namespace CreativeCherry\HotelLandingPages\Model;


abstract class BaseModel implements \ArrayAccess
{

    /**
     * @var array The properties set on the model
     */
    private $properties = array();


    /**
     * Constructor sets the names and values of the properties.
     * The names will key the properties, and the values will be the value
     * @param array $property_names The properties allowed on this model
     * @param array $property_values The property values set on this model
     */
    public function __construct($property_names, $property_values)
    {
        $property_names = array_map(function($item) {
            return mb_strtolower(preg_replace('/[^\w+\d+]/i', "", $item));
        }, $property_names);

        $this->properties = array_combine($property_names, $property_values);
        if ($this->properties == false) {
            throw new \InvalidArgumentException("Property name and value arrays must be the same length");
        }
    }

    /**
     * @param string $name The name of the property to access
     * @return mixed
     */
    public function __get($name)
    {
        $name = mb_strtolower(preg_replace('/[^\w+\d+]/i', "", $name));
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        } else {
            return null;
        }
    }

    /**
     * Set a property equal to a value. Property must have been set up at
     * object construction time
     * @param $name The property we wish to update
     * @param $value The value to assign to the property
     */
    public function __set($name, $value)
    {
        $name = mb_strtolower(preg_replace('/[^\w+\d+]/i', "", $name));
        if (array_key_exists($name, $this->properties)) {
            $this->properties[$name] = $value;
        } else {
            throw new \InvalidArgumentException("This property does not exists.");
        }
    }


    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->properties[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return isset($this->properties[$offset]) ? $this->properties[$offset] : null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->properties[] = $value;
        } else {
            $this->properties[$offset] = $value;
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->properties["$offset"]);
    }
}