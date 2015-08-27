#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';
$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($loop);
$conns = new \SplObjectStorage();

$ip = array_key_exists(1, $argv) ? $argv[1] : '127.0.0.1';
$port = array_key_exists(2, $argv) ? $argv[2] : 1337;

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
                    $current->write('ERROR ' . get_class($e) . PHP_EOL);
                }
            }
        }
    });
    $conn->on('end', function () use ($conns, $conn) {
        $conns->detach($conn);
    });
});
echo "Socket server listening on port " . $port . ".\n";
echo "You can connect to it by running: telnet " . $ip . " " . $port . "\n";
$socket->listen($port, $ip);
$loop->run();
