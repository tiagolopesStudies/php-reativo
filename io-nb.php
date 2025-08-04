<?php

/** @var array<resource> $streams */
$streams = [
    fopen(__DIR__ . '/file1.txt', 'r'),
    fopen(__DIR__ . '/file2.txt', 'r'),
];

foreach ($streams as $stream) {
    stream_set_blocking(stream: $stream, enable: false);
}

do {
    $streamsCopy   = $streams;
    $streamsNumber = stream_select(
        read: $streamsCopy,
        write: $write,
        except: $except,
        seconds: 0,
        microseconds: 200_000
    );

    if ($streamsNumber === 0) {
        // Pode realizar outras tarefas enquanto os arquivos são carregados
        continue;
    }

    foreach ($streamsCopy as $key => $stream) {
        echo fgets($stream);
        unset($streams[$key]);
    }
} while (! empty($streams));
echo 'Li todos os arquivos' . PHP_EOL;
