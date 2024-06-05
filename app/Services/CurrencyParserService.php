<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;

class CurrencyParserService {
    public function extractCurrencyData($html, $criteria) {
        $crawler = new Crawler($html);
        $filteredCodes = [];

        $crawler->filter('.wikitable tbody tr')->each(function (Crawler $node, $i) use ($criteria, &$filteredCodes) {
            if ($i > 0) { // Ignora o cabeçalho da tabela
                $cells = $node->filter('td');
                if ($cells->count() > 0) {
                    $isoCode = trim($cells->eq(0)->text());
                    $numericCode = trim($cells->eq(1)->text());
                    
                    if ($this->matchesCriteria($isoCode, $numericCode, $criteria)) {
                        $currencyData = [
                            'code' => $isoCode,
                            'number' => $numericCode,
                            'decimal' => trim($cells->eq(2)->text()),
                            'currency' => trim($cells->eq(3)->text()),
                            'currency_locations' => $this->extractLocationData($cells->eq(4))
                        ];
                        $filteredCodes[] = $currencyData;
                    }
                }
            }
        });

        return $filteredCodes;
    }

    private function matchesCriteria($isoCode, $numericCode, $criteria) {
        return ((isset($criteria['code']) && $criteria['code'] === $isoCode) ||
                (isset($criteria['code_list']) && in_array($isoCode, $criteria['code_list'])) ||
                (isset($criteria['number']) && $criteria['number'] == $numericCode) ||
                (isset($criteria['number_list']) && in_array($numericCode, $criteria['number_list'])));
    }

    private function extractLocationData(Crawler $node) {
        $locationsData = [];
        if ($node->filter('span')->count() > 0) {
            $node->filter('span')->each(function (Crawler $span) use (&$locationsData) {
                $image = $span->filter('.mw-image-border img')->first();
                $location = $span->nextAll()->filter('a')->first();
                
                $locationData = [
                    'location' => $location->count() > 0 ? $location->html() : '',
                    'icon' => $image->count() > 0 ? $image->attr('src') : '',
                ];
                if (!empty($locationData['location'])) {
                    $locationsData[] = $locationData;
                }
            });
        }
        return $locationsData;
    }
}
