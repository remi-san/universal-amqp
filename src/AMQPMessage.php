<?php
namespace UniversalAMQP;

class AMQPMessage {

    /**
     * @var string
     */
    public $body;

    /**
     * @var string[]
     */
    public $properties;

    /**
     * @var string[]
     */
    public $delivery_info;

    /**
     * Look for additional properties in the 'properties' dictionary,
     * and if present - the 'delivery_info' dictionary.
     *
     * @param string $name
     * @throws \OutOfBoundsException
     * @return mixed|AMQPChannel
     */
    public function get($name)
    {
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }

        if (isset($this->delivery_info[$name])) {
            return $this->delivery_info[$name];
        }

        throw new \OutOfBoundsException(sprintf(
            'No "%s" property',
            $name
        ));
    }
} 