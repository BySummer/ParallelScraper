<?php

namespace BySummer\ParallelScraper\Client\Request;

use BySummer\ParallelScraper\Client\Response\ResponseJsonFactory;
use BySummer\ParallelScraper\Config;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\Process\Process;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class RequestManager
{
    private CurlHttpClient      $client;
    private ResponseJsonFactory $jsonFactory;

    public function __construct()
    {
        $this->client      = new CurlHttpClient();
        $this->jsonFactory = new ResponseJsonFactory();
    }

    public function async(array $urls): array
    {
        $result   = [];
        $channels = [];

        foreach ($urls as $key => $url) {
            $process = new Process(['php', __DIR__ . '/../' . Config::QUERY_PATH, '--url=' . $url]);
            $process->start();

            $channels[$key] = $process;
        }

        while (count($channels) > 0) {
            foreach ($channels as $key => $channel) {
                if ($channel instanceof Process && !$channel->isRunning()) {
                    $result[$key] = $channel->getOutput();
                    unset($channels[$key]);
                }
            }
        }

        return $result;
    }

    public function query(string $url): string
    {
        try {
            $response = $this->client->request(
                'GET',
                Config::SERVER_ADDRESS,
                [
                    'query' => [
                        'url' => $url,
                    ],
                ]
            )->getContent();

            return $this->jsonFactory->createSuccessResponse(
                json_decode($response, true)
            );
        } catch (TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            return $this->jsonFactory->createFailureResponse([$e->getMessage()]);
        }
    }
}