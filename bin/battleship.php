<?php

require __DIR__.'/../vendor/autoload.php';
$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($loop);
$conns = new \SplObjectStorage();

$socket->on('connection', function ($conn) use ($conns) {
    $conn->id = '';
    $conns->attach($conn);
    $conn->on('data', function ($data) use ($conns, $conn) {
        foreach ($conns as $current) {
            if ($conn === $current) {
                $service = new \Wecamp\FlyingLiqourice\Service\ServiceListener($data, $conn->id);
                try {
                    $current->write($service->run() . PHP_EOL);
                    $conn->id = (string) $service->id();

                } catch (\InvalidArgumentException $e) {
                    $current->write('ERR You did something wrong' . PHP_EOL);
                }
            }
        }
    });
    $conn->on('end', function () use ($conns, $conn) {
        $conns->detach($conn);
    });
});
$port = 4000;
$ip = '192.168.11.64';
echo "Socket server listening on port " . $port . ".\n";
echo "You can connect to it by running: telnet " . $ip . " " . $port . "\n";
$socket->listen($port, $ip);
$loop->run();
