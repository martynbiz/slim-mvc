<?php
namespace MartynBiz\Flash\Storage;

class Session implements ArrayObject {

    /**
     * @var ArrayAccess
     */
    protected $container;

    /**
     * @param string $namespace
     */
    public function __construct($namespace='__martynbiz_flash_session')
    {
        $_SESSION[$namepace] = array();

        $this->container = &$_SESSION[$namepace];
    }

    public function offsetSet($offset, $value)
    {
        $this->container[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
}
