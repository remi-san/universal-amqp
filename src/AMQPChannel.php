<?php
namespace UniversalAMQP;

abstract class AMQPChannel
{
    /**
     * @var array
     */
    public $callbacks;

    /**
     * @param int     $size
     * @param int     $count
     * @param boolean $global
     *
     * @return mixed
     */
    public abstract function basic_qos($size, $count, $global = null);

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
    public abstract function basic_publish(
        $msg,
        $exchange = '',
        $routing_key = '',
        $mandatory = false,
        $immediate = false,
        $ticket = null
    );

    /**
     * @param  string   $queue
     * @param  string   $consumer_tag
     * @param  bool     $no_local
     * @param  bool     $no_ack
     * @param  bool     $exclusive
     * @param  bool     $nowait
     * @param  callable $callback
     * @param  string   $ticket
     * @param  array    $arguments
     * @return mixed
     */
    public abstract function basic_consume($queue = '',
                                  $consumer_tag = '',
                                  $no_local = false,
                                  $no_ack = false,
                                  $exclusive = false,
                                  $nowait = false,
                                  callable $callback = null,
                                  $ticket = null,
                                  $arguments = array());

    /**
     * @param  string $deliveryTag
     * @return mixed
     */
    public abstract function basic_ack($deliveryTag);

    /**
     * @param  string  $deliveryTag
     * @param  boolean $requeue
     * @return mixed
     */
    public abstract function basic_reject($deliveryTag, $requeue = true);

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
    public abstract function queue_declare(
        $queue = '',
        $passive = false,
        $durable = false,
        $exclusive = false,
        $auto_delete = true,
        $nowait = false,
        $arguments = null,
        $ticket = null
    );

    /**
     * @return mixed
     */
    public abstract function wait();

    /**
     * @return mixed
     */
    public abstract function close();
} 