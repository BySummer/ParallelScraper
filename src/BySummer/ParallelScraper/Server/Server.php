<?php

namespace BySummer\ParallelScraper\Server;

use BySummer\ParallelScraper\Client\ChromeClient;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server as SwooleServer;
use BySummer\ParallelScraper\Config;

class Server
{
    private SwooleServer $server;
    private ChromeClient $chrome;

    public function __construct()
    {
        $this->server = new SwooleServer(Config::SERVER_IP, Config::SERVER_PORT);
        $this->chrome = new ChromeClient();
        $this->server->set(['worker_num' => 1, 'http_compression' => true]);
    }

    public function getServer(): SwooleServer
    {
        return $this->server;
    }

    public function run()
    {
        $client = $this->chrome->getClient();

        $this->server->on('Request', function (Request $request, Response $response) use ($client) {
            new Controller($request, $response, $client);
        });

        $this->server->start();
    }
}