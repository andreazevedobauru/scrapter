<?php

namespace Tests\Unit;

use App\Services\CurrencyParserService;
use Tests\TestCase;

class CurrencyParserServiceTest extends TestCase
{
    public function testExtractCurrencyData()
    {
        // Carregar o HTML de um arquivo de fixture
        $html = file_get_contents(__DIR__ . '/../Fixtures/wikipediaISO_4217.html');
        $service = new CurrencyParserService();

        $validated = ['code' => 'USD']; // Critérios simples para testar
        $results = $service->extractCurrencyData($html, $validated);

        // Asserts para verificar se os dados extraídos estão corretos
        $this->assertIsArray($results);
        $this->assertCount(1, $results); // Supõe que um resultado seja encontrado
        $this->assertEquals('USD', $results[0]['code']);
        $this->assertEquals('840', $results[0]['number']);
        $this->assertEquals('Dólar americano', $results[0]['currency']);
    }
}
