<?php
namespace UniversalAMQP\Lib;

use UniversalAMQP\AMQPChannel;
use UniversalAMQP\AMQPMessage;

class AMQPLibChannel extends AMQPChannel
{
    /**
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    private $channel;

    public function __construct(\PhpAmqpLib\Channel\AMQPChannel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * @param int $size
     * @param int $count
     * @param boolean $global
     *
     * @return mixed
     */
    public function basic_qos($size, $count, $global = null)
    {
        return $this->channel->basic_qos($size, $count, $global);
    }

    /**
     * Publishes a message
     *
     * @param AMQPMessage $msg
     * @param string $exchange
     * @param string $routing_key
     * @param bool $mandatory
     * @param bool $immediate
     * @param null $ticket
     */
    public function basic_publish(
        $msg,
        $exchange = '',
        $routing_key = '',
        $mandatory = false,
        $immediate = false,
        $ticket = null
    )
    {
        $message = new \PhpAmqpLib\Message\AMQPMessage($msg->body, $msg->get_properties());
        $this->channel->basic_publish($message, $exchange, $routing_key, $mandatory, $immediate, $ticket);
    }

    /**
     * @param  string $queue
     * @param  string $consumer_tag
     * @param  bool $no_local
     * @param  bool $no_ack
     * @param  bool $exclusive
     * @param  bool $nowait
     * @param  callable $callback
     * @param  string $ticket
     * @param  array $arguments
     * @return mixed
     */
    public function basic_consume(
        $queue = '',
        $consumer_tag = '',
        $no_local = false,
        $no_ack = false,
        $exclusive = false,
        $nowait = false,
        callable $callback = null,
        $ticket = null,
        $arguments = array())
    {
        return $this->channel->basic_consume(
            $queue,
            $consumer_tag,
            $no_local,
            $no_ack,
            $exclusive,
            $nowait,
            function(\PhpAmqpLib\Message\AMQPMessage $message) use ($callback) {
                return $callback(new AMQPMessage($message->body, $message->get_properties(), $message->delivery_info));
            },
            $ticket,
            $arguments
        );
    }

    /**
     * @param  string $deliveryTag
     * @return mixed
     */
    public function basic_ack($deliveryTag)
    {
        return $this->channel->basic_ack($deliveryTag);
    }

    /**
     * @param  string $deliveryTag
     * @param  boolean $requeue
     * @return mixed
     */
    public function basic_reject($deliveryTag, $requeue = true)
    {
        return $this->channel->basic_reject($deliveryTag, $requeue);
    }

    /**
     * Declares queue, creates if needed
     *
     * @param string $queue
     * @param bool $passive
     * @param bool $durable
     * @param bool $exclusive
     * @param bool $auto_delete
     * @param bool $nowait
     * @param null $arguments
     * @param null $ticket
     * @return mixed|null
     */
    public function queue_declare(
        $queue = '',
        $passive = false,
        $durable = false,
        $exclusive = false,
        $auto_delete = true,
        $nowait = false,
        $arguments = null,
        $ticket = null
    )
    {
        return $this->queue_declare($queue, $passive, $durable, $exclusive, $auto_delete, $nowait, $arguments, $ticket);
    }

    /**
     * @return mixed
     */
    public function wait()
    {
        return $this->channel->wait();
    }

    /**
     * @return mixed
     */
    public function close()
    {
        return $this->channel->close();
    }
} 