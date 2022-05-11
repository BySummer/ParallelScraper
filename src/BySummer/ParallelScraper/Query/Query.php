<?php

namespace BySummer\ParallelScraper\Query;

class Query
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function execute()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:9501?url=" . $this->url);

        $status = curl_exec($ch);

        if(false === $status) {
            echo json_encode(['status' => 0, 'errors' => [
                    "Сервер не запущен."
            ]], JSON_UNESCAPED_UNICODE) . "\n";
        }

        curl_close($ch);
    }
}

$opts = getopt("", ["url:"]);

if(!isset($opts['url'])) {
    echo json_encode(['status' => 0, 'errors' => [
            "Укажите --url флаг."
    ]], JSON_UNESCAPED_UNICODE) . "\n";
}

$query = new Query($opts['url']);
$query->execute();