<?php
namespace UniversalAMQP\Extension;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use UniversalAMQP\AMQPChannel;
use UniversalAMQP\AMQPConnection;

class AMQPExtensionConnection implements AMQPConnection
{
    /**
     * @var \AMQPConnection
     */
    private $connection;

    public function __construct($host, $port, $user, $password)
    {
        $credentials = array(
           'host'  => $host,
           'port'  => $port,
           'vhost' => '/',
           'login' => $user,
           'password' => $password,
           'read_timeout'  => 0,
           'write_timeout' => 0,
           'connect_timeout' => 0
        );

        $this->connection = new \AMQPConnection($credentials);
    }

    /**
     * @param  int $channelId
     *
     * @return AMQPChannel
     */
    public function channel($channelId = null)
    {
        return new AMQPExtensionChannel(new \AMQPChannel($this->connection));
    }

    /**
     * @return mixed
     */
    public function close()
    {
        $this->connection->disconnect();
    }
} 