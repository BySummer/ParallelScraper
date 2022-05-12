<?php

namespace BySummer\ParallelScraper;

use BySummer\ParallelScraper\Client\Request\RequestManager;

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