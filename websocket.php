<?php

require_once __DIR__ . '/vendor/autoload.php';

use Ratchet\Http\HttpServer;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\MessageComponentInterface;
use Ratchet\WebSocket\WsServer;
use Ratchet\ConnectionInterface;

$chatComponent = new class implements MessageComponentInterface
{
    private SplObjectStorage $connections;

    public function __construct()
    {
        $this->connections = new SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        echo 'New connection!' . PHP_EOL;
        $this->connections->attach($conn);
    }

    public function onClose(ConnectionInterface $conn): void
    {
        echo 'Connection closed!' . PHP_EOL;
        $this->connections->detach($conn);
    }

    public function onError(ConnectionInterface $conn, Exception $e): void
    {
        echo 'Error: ' . $e->getMessage() . PHP_EOL;
    }

    public function onMessage(ConnectionInterface $conn, MessageInterface $msg): void
    {
        /** @var ConnectionInterface $connection */
        foreach ($this->connections as $connection) {
            if ($connection !== $conn) {
                $connection->send((string) $msg);
            }
        }
    }
};

$server = IoServer::factory(
    component: new HttpServer(
        new WsServer($chatComponent)
    ),
    port: 8002
);

$server->run();
