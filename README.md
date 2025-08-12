# PHP Reativo

Este projeto é uma aplicação para praticar programação reativa em PHP, explorando conceitos como programação assíncrona, IO não bloqueante, sockets, uso de bibliotecas como ReactPHP e o conceito de WebSocket.

## O que é Programação Reativa?

Programação reativa é um paradigma que lida com fluxos de dados e propagação de mudanças. Em vez de executar comandos de forma sequencial e bloqueante, a programação reativa permite que aplicações respondam a eventos de forma assíncrona, tornando o sistema mais eficiente e responsivo.

### Programação Reativa vs Programação Assíncrona

* **Programação Assíncrona:** Permite que operações demoradas (como acesso a arquivos ou redes) sejam executadas em paralelo ao restante do código, sem bloquear a aplicação.
* **Programação Reativa:** Vai além da assíncrona, focando em fluxos de dados/eventos e na reação a mudanças, facilitando a composição e manipulação desses fluxos.

## IO Não Bloqueante

Em PHP tradicional, operações de IO (como leitura de arquivos ou sockets) bloqueiam a execução até que sejam concluídas. Com IO não bloqueante, a aplicação pode continuar executando outras tarefas enquanto espera a resposta dessas operações.

**Exemplo tradicional (bloqueante):**

```php
$conteudo = file_get_contents('file1.txt');
echo $conteudo;
```

**Exemplo com ReactPHP (não bloqueante):**

```php
require 'vendor/autoload.php';

use React\EventLoop\Factory;
use React\Stream\ReadableResourceStream;

$loop = React\EventLoop\Factory::create();
$stream = new ReadableResourceStream(fopen('file1.txt', 'r'), $loop);
$stream->on('data', function ($data) {
	echo $data;
});
$loop->run();
```

## Sockets

Sockets permitem comunicação entre processos ou máquinas. Em programação reativa, é comum criar servidores de socket que lidam com múltiplas conexões simultaneamente, sem bloquear a execução.

**Exemplo de servidor de socket com ReactPHP:**

Veja o arquivo `socket-server.php` deste projeto:

```php
require 'vendor/autoload.php';

use React\EventLoop\Factory;
use React\Socket\SocketServer;

$loop = Factory::create();
$socket = new SocketServer('127.0.0.1:8080', [], $loop);
$socket->on('connection', function ($conn) {
	$conn->write("Olá, cliente!\n");
	$conn->on('data', function ($data) use ($conn) {
		$conn->write("Você disse: $data");
	});
});
$loop->run();
```

## Bibliotecas para Programação Reativa em PHP

### ReactPHP

O [ReactPHP](https://reactphp.org/) é uma das principais bibliotecas para programação reativa e assíncrona em PHP. Ele fornece um event loop, abstrações para streams, sockets, HTTP, timers e muito mais.

**Principais componentes:**
- `react/event-loop`: núcleo do event loop.
- `react/stream`: manipulação de streams assíncronas.
- `react/socket`: criação de servidores e clientes TCP/UDP.
- `cboden/ratchet`: implementação de WebSocket baseada em ReactPHP.

**Exemplo de uso do event loop:**

```php
require 'vendor/autoload.php';

use React\EventLoop\Factory;

$loop = Factory::create();
$loop->addTimer(2, function () {
	echo "Executado após 2 segundos!\n";
});
$loop->run();
```

## WebSocket

WebSocket é um protocolo que permite comunicação bidirecional em tempo real entre cliente e servidor, ideal para aplicações como chats, jogos e dashboards.

**Exemplo de servidor WebSocket com Ratchet (cboden/ratchet):**

Veja o arquivo `websocket.php` deste projeto:

```php
require 'vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
	public function onOpen(ConnectionInterface $conn) {
		echo "Nova conexão! ({$conn->resourceId})\n";
	}
	public function onMessage(ConnectionInterface $from, $msg) {
		$from->send("Você disse: $msg");
	}
	public function onClose(ConnectionInterface $conn) {
		echo "Conexão {$conn->resourceId} fechada\n";
	}
	public function onError(ConnectionInterface $conn, \Exception $e) {
		echo "Erro: {$e->getMessage()}\n";
	}
}

$server = IoServer::factory(
	new WsServer(new Chat()),
	8080
);
$server->run();
```

## Conclusão

Este projeto explora os principais conceitos de programação reativa em PHP, utilizando ReactPHP e Ratchet para criar aplicações assíncronas, não bloqueantes e em tempo real. Explore os arquivos do projeto para ver exemplos práticos de cada conceito!
