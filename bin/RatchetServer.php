#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/app.php';

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;


$loop = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Wecamp\FlyingLiqourice\Battleship()
        )
    ),
    8080
);

$loop->run();
