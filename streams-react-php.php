<?php

require_once __DIR__ . '/vendor/autoload.php';

use React\EventLoop\Loop;
use React\Stream\{DuplexResourceStream, ReadableResourceStream, ReadableStreamInterface};

$loop = Loop::get();

/** @var array<ReadableStreamInterface> $streams */
$streams = [
    new ReadableResourceStream(stream: stream_socket_client('tcp://localhost:8001'), loop: $loop),
    new ReadableResourceStream(stream: fopen(__DIR__ . '/file1.txt', 'r'), loop: $loop),
    new ReadableResourceStream(stream: fopen(__DIR__ . '/file2.txt', 'r'), loop: $loop),
];

$httpStream =  new DuplexResourceStream(stream: stream_socket_client('tcp://localhost:8080'), loop: $loop);
$httpStream->write('GET /http-server.php HTTP/1.1' . PHP_EOL . PHP_EOL);
$httpStream->on('data', function ($data) {
    echo $data;
});

foreach ($streams as $stream) {
    $stream->on('data', function ($data) {
        echo $data . PHP_EOL;
    });
}

$loop->run();
