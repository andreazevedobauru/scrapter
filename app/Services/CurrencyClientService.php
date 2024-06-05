<?php

namespace App\Services;

use GuzzleHttp\Client;

class CurrencyClientService {
    private $client;

    public function __construct() {
        $this->client = new Client();
    }

    public function fetchCurrencyHtml() {
        $response = $this->client->request('GET', 'https://pt.wikipedia.org/wiki/ISO_4217');
        return $response->getBody()->getContents();
    }
}