<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class UtilsService
{
    /**
     * @var HttpClientInterface.
     */
    private HttpClientInterface $httpClient;

    /**
     * @param HttpClientInterface
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return array
     */
    public function getPositions(): array
    {
        $response = $this->httpClient->request('GET', 'https://ibillboard.com/api/positions');

        return $response->toArray();
    }
}