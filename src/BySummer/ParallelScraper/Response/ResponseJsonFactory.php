<?php

namespace BySummer\ParallelScraper\Response;

class ResponseJsonFactory
{
    /**
     * @param array|null $data
     * @param array|null $meta
     * @return string
     */
    public function createSuccessResponse(array $data = null, array $meta = null): string
    {
        $responseData = [
            'success' => true,
        ];

        if ($data !== null) {
            $responseData['data'] = $data;
        }

        if ($meta !== null) {
            $responseData['meta'] = $meta;
        }

        return $this->createJsonResponse($responseData);
    }

    /**
     * @param array $errors
     * @return string
     */
    public function createFailureResponse(array $errors = []): string
    {
        $responseData = [
            'success' => false,
            'errors'  => $errors,
        ];

        return $this->createJsonResponse($responseData);
    }

    /**
     * @param array $data
     * @return string
     */
    protected function createJsonResponse(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}