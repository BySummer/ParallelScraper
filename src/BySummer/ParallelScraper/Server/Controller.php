<?php

namespace BySummer\ParallelScraper\Server;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Symfony\Component\Panther\Client;

class Controller
{
    public function __construct(Request $request, Response $response, Client $client) {

        if(!isset($request->get['url'])) {
            $response->write("Укажите Url.");
            return;
        }

        $response->write(
            $client->request('GET', $request->get['url'])->text()
        );
    }
}