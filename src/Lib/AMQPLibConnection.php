<?php
namespace UniversalAMQP\Lib;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use UniversalAMQP\AMQPChannel;
use UniversalAMQP\AMQPConnection;

class AMQPLibConnection implements AMQPConnection
{
    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    public function __construct($host, $port, $user, $password)
    {
        $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
    }

    /**
     * @param  int $channelId
     *
     * @return AMQPChannel
     */
    public function channel($channelId = null)
    {
        return new AMQPLibChannel($this->connection->channel());
    }

    /**
     * @return mixed
     */
    public function close()
    {
        $this->connection->close();
    }
} 