<?php
namespace MartynBiz;

/**
 * Flash messages. Slight variation in that this will store a message until it
 * is accessed - whether that is a next http request, or same request. Simply
 * when get method is called the message is wiped from session.
 * TODO move this to martynbiz\php-flash
 */
class Flash
{
    /**
     * Message storage
     *
     * @var ArrayObject
     */
    protected $storage;

    /**
     * @param string $storage Name to store messages in session
     */
    public function __construct($storage=null)
    {
        // if storage is not defined, create ArrayObject (not persistent)
        if (is_null($storage)) {
            $storage = new \ArrayObject();
        } elseif (! $storage instanceof \ArrayAccess) {
            throw new \Exception('$storage must be an instance of ArrayAccess.');
        }

        $this->storage = $storage;
    }

    /**
     * Add flash message
     *
     * @param string $key The key to store the message under
     * @param string $message Message to show on next request
     */
    public function addMessage($key, $message)
    {
        // create entry in the session
        $this->storage[$key] = $message;
    }

    /**
     * Get flash messages, and reset storage
     * @return array Messages to show for current request
     */
    public function flushMessages()
    {
        $messages = $this->storage->getArrayCopy();

        // clear storage items
        foreach ($this->storage as $key => $value) {
            unset($this->storage[$key]);
        }

        return $messages;
    }
}
