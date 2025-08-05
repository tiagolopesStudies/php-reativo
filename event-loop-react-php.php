<?php

require_once __DIR__ . '/vendor/autoload.php';

use React\EventLoop\Loop;

$loop = Loop::get();
$loop->addPeriodicTimer(
    interval: 2,
    callback: function () {
        echo 'Tick após 2 segundos' . PHP_EOL;
    }
);

Loop::addTimer(
    interval: 10,
    callback: function () use ($loop) {
        echo 'Cancelando o timer' . PHP_EOL;
        $loop->stop();
    }
);

$loop->run();
