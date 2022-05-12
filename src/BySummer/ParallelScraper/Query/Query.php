<?php

namespace BySummer\ParallelScraper\Query;

use BySummer\ParallelScraper\Config;
use BySummer\ParallelScraper\Response\ResponseJsonFactory;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Query
{
    private array               $opts;
    private ResponseJsonFactory $jsonFactory;
    private CurlHttpClient      $client;

    public function __construct(array $opts)
    {
        $this->opts        = $opts;
        $this->jsonFactory = new ResponseJsonFactory();
        $this->client      = new CurlHttpClient();
    }

    public function getUrl(): ?string
    {
        return $this->opts['url'] ?? null;
    }

    public function execute(): string
    {
        $url = $this->getUrl();

        if (empty($url)) {
            return $this->jsonFactory->createFailureResponse(
                ["Укажите URL флаг: --url https://..."]
            );
        }

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

            return $this->jsonFactory->createSuccessResponse([$response]);
        } catch (TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            return $this->jsonFactory->createFailureResponse([$e->getMessage()]);
        }
    }
}

$query = new Query(
    getopt("", ["url:"])
);

echo $query->execute();