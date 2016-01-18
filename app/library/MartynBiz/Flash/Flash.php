<?php
namespace MartynBiz;

/**
 * Slight variation on flash messages in that 1) it can save to session but doesn't
 * require a new http request for messages to be available, are accessible as soon as
 * they are added until they are fetched (flushMessages), and 2) messages are flushed
 * when they are accessed one time.
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

        // clear storage items. will attempt to handle multiple types here as
        // it seems that some types prefer get_object_vars
        if ($this->storage instanceof \ArrayObject) {
            foreach (get_object_vars($this->storage) as $key => $value) {
                $this->storage->offsetUnset($key);
            }
        } else {
            foreach ($this->storage as $key => $value) {
                $this->storage->offsetUnset($key);
            }
        }

        return $messages;
    }
}
