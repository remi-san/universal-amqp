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
    private $properties;

    /**
     * @var string[]
     */
    public $delivery_info;

    /**
     * @param $body
     * @param array $properties
     * @param array $deliveryInfo
     */
    public function __construct($body, array $properties = array(), array $deliveryInfo = array())
    {
        $this->body = $body;
        $this->properties = $properties;
        $this->delivery_info = $deliveryInfo;
    }

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

    /**
     * @return string[]
     */
    public function get_properties()
    {
        return $this->properties;
    }
} 