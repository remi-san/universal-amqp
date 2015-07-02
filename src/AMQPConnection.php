<?php
namespace UniversalAMQP;

interface AMQPConnection
{
    /**
     * @param  int $channelId
     *
     * @return AMQPChannel
     */
    public function channel($channelId = null);

    /**
     * @return mixed
     */
    public function close();
} 