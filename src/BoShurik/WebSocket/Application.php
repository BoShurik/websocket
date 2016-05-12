<?php
/**
 * User: boshurik
 * Date: 06.05.16
 * Time: 13:06
 */

namespace BoShurik\WebSocket;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Application implements WampServerInterface
{
    /**
     * A lookup of all the topics clients have subscribed to
     */
    protected $subscribedTopics = array();

    public function onMessage($message)
    {
        echo sprintf("%s\n", __METHOD__);

        echo "Message $message\n";

        $messageData = json_decode($message, true);
        if (!isset($this->subscribedTopics[$messageData['category']])) {
            return;
        }

        $topic = $this->subscribedTopics[$messageData['category']];
        $topic->broadcast($messageData['message']);
    }

    /**
     * @inheritDoc
     */
    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        echo sprintf("%s\n", __METHOD__);

        $this->subscribedTopics[$topic->getId()] = $topic;
    }

    /**
     * @inheritDoc
     */
    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        echo sprintf("%s\n", __METHOD__);

        unset($this->subscribedTopics[$topic->getId()]);
    }

    /**
     * @inheritDoc
     */
    public function onOpen(ConnectionInterface $conn)
    {

    }

    /**
     * @inheritDoc
     */
    public function onClose(ConnectionInterface $conn)
    {

    }

    /**
     * @inheritDoc
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {

    }

    /**
     * @inheritDoc
     */
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {

    }

    /**
     * @inheritDoc
     */
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {

    }
}