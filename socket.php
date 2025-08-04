<?php

/** @var array<resource> $streams */
$streams = [
    stream_socket_client('tcp://localhost:8080'),
    fopen(__DIR__ . '/file1.txt', 'r'),
    fopen(__DIR__ . '/file2.txt', 'r'),
];

fwrite(stream: $streams[0], data: 'GET /http-server.php HTTP/1.1' . PHP_EOL . PHP_EOL);
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
        $content = stream_get_contents($stream);
        $endPositionHttp = strpos($content, '\r\n\r\n');
        if ($endPositionHttp === false) {
            echo $content;
        } else {
            echo substr(string: $content, offset: $endPositionHttp + 4);
        }
        unset($streams[$key]);
    }
} while (! empty($streams));
echo 'Li todas as streams' . PHP_EOL;
