<?php

namespace BySummer\ParallelScraper;

use Symfony\Component\Process\Process;

require_once 'vendor/autoload.php';

class ParallelScraper
{
    public static function asyncRequest(array $urls): array
    {
        $result = [];
        $channels = [];

        foreach ($urls as $url) {
            $path = __DIR__. '/Query/Query.php';
            $process = new Process(['php', $path, '--url=' . $url]);
            $process->start();
            $channels[] = $process;
        }

        while(count($channels) > 0) {
            foreach ($channels as $key => $channel) {
                if ($channel instanceof Process && !$channel->isRunning()) {
                    $result[$key] = [$channel->getOutput()];
                    unset($channels[$key]);
                }
            }
        }

        var_dump($result);
        return $result;
    }
}

$class = new ParallelScraper();

$class::asyncRequest(
    [
        'https://api64.ipify.org?format=json',
        'https://api64.ipify.org?format=json',
        'https://api64.ipify.org?format=json',
        'https://api64.ipify.org?format=json',
        'https://api64.ipify.org?format=json',
        'https://api64.ipify.org?format=json',
        'https://api64.ipify.org?format=json',
        'https://api64.ipify.org?format=json',
        'https://api64.ipify.org?format=json',
        'https://api64.ipify.org?format=json',
        'https://api64.ipify.org?format=json',
        'https://api64.ipify.org?format=json',
        'https://api64.ipify.org?format=json',
    ]
);