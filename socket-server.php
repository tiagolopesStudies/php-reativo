<?php

$socket = stream_socket_server('tcp://localhost:8001');
$con    = stream_socket_accept(socket: $socket, timeout: 20);

$waitTime = rand(1, 5);
sleep($waitTime);

fwrite(stream: $con, data: "Resposta do servidor que levou $waitTime segundos" . PHP_EOL);
fclose(stream: $con);
