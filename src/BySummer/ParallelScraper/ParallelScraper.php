<?php

namespace BySummer\ParallelScraper;

use BySummer\ParallelScraper\Client\Request\RequestManager;

require_once 'vendor/autoload.php';

class ParallelScraper
{
    /**
     * Асинхронное выполнение массива URL адресов
     * Возвращает результат с тем же ключом,
     * Который был передан в исходном массиве
     * @param array $urls
     * @return array
     */
    public static function async(array $urls): array
    {
        return (new RequestManager())->async($urls);
    }

    /**
     * Синхронное выполнение одного URL адреса
     * @param string $url
     * @return string
     */
    public static function query(string $url): string
    {
        return (new RequestManager())->query($url);
    }
}

$class = new ParallelScraper();

$response = $class::query('https://api64.ipify.org?format=json');

var_dump($response);