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

        return $result;
    }
}