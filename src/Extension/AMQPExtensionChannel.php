<?php
namespace UniversalAMQP\Extension;

use UniversalAMQP\AMQPChannel;
use UniversalAMQP\AMQPMessage;

class AMQPExtensionChannel extends AMQPChannel {

    /**
     * @var \AMQPChannel
     */
    private $channel;

    /**
     * @var \AMQPQueue
     */
    private $queue;

    /**
     * @param \AMQPChannel $channel
     * @param \AMQPQueue   $queue
     */
    public function __construct(\AMQPChannel $channel, \AMQPQueue $queue = null)
    {
        $this->channel = $channel;
        if ($queue) {
            $this->queue = $queue;
        } else {
            $this->queue = new \AMQPQueue($this->channel);
        }
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
        $this->channel->qos($size, $count);
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
        $flags  = AMQP_NOPARAM;
        $flags += $mandatory ? AMQP_MANDATORY : 0;
        $flags += $immediate ? AMQP_IMMEDIATE : 0;
        
        $xchange = new \AMQPExchange($this->channel);
        $xchange->setName($exchange);
        $xchange->publish($msg->body, $routing_key, $flags, $msg->get_properties());
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
    public function basic_consume($queue = '',
                                  $consumer_tag = '',
                                  $no_local = false,
                                  $no_ack = false,
                                  $exclusive = false,
                                  $nowait = false,
                                  callable $callback = null,
                                  $ticket = null,
                                  $arguments = array())
    {
        $q = new \AMQPQueue($this->channel);
        $q->setName($queue);
        $q->declareQueue();
        
        $flags  = AMQP_NOPARAM;
        $flags += $no_local ? AMQP_NOLOCAL : 0;
        $flags += $no_ack ? AMQP_AUTOACK : 0;
        $flags += $exclusive ? AMQP_EXCLUSIVE : 0;
        $flags += $nowait ? AMQP_NOWAIT : 0;
        
        $q->consume(
            function($message, \AMQPQueue $q) use ($callback, $flags, $consumer_tag) {
                $deliveryInfo = array(
                    'channel' => $q,
                    'delivery_tag' => '' // TODO find delivery tag
                );
                return $callback(new AMQPMessage($message, array(), $deliveryInfo));
            },
            $flags,
            $consumer_tag
        );
    }

    /**
     * @param  string $deliveryTag
     * @return mixed
     */
    public function basic_ack($deliveryTag)
    {
        $this->queue->ack($deliveryTag);
    }

    /**
     * @param  string $deliveryTag
     * @param  boolean $requeue
     * @return mixed
     */
    public function basic_reject($deliveryTag, $requeue = true)
    {
        $this->queue->reject($deliveryTag, ($requeue) ? AMQP_REQUEUE : AMQP_NOPARAM);
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
        $flags = 0;
        $flags += $passive ? AMQP_PASSIVE : 0;
        $flags += $durable ? AMQP_DURABLE : 0;
        $flags += $exclusive ? AMQP_EXCLUSIVE : 0;
        $flags += $auto_delete ? AMQP_AUTODELETE : 0;
        $flags += $nowait ? AMQP_NOWAIT : 0;

        $queue = new \AMQPQueue($this->channel);
        $queue->setName($queue);
        $queue->setFlags($flags);
        $queue->setArguments($arguments);
        $queue->declareQueue();

        return array($queue->getName(), null, null);
    }

    /**
     * @return mixed
     */
    public function wait()
    {
        // TODO: Implement wait() method.
    }

    /**
     * @return mixed
     */
    public function close()
    {
        $this->channel->getConnection()->disconnect();
    }


} 