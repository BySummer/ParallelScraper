<?php

namespace BySummer\ParallelScraper\Client;

use Symfony\Component\Panther\Client;

class ChromeClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = Client::createChromeClient(
            null, ['--headless', '--disable-gpu', '--no-sandbox']
        );
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}