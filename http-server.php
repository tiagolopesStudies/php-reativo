<?php

$waitTime = rand(1, 5);
sleep($waitTime);

echo "Resposta do servidor que levou $waitTime segundos" . PHP_EOL;
