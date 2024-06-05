<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencyRouteTest extends TestCase
{
    use RefreshDatabase;

    public function testCurrencyRouteWithValidCode()
    {
        // Testa com um código de moeda válido
        $response = $this->json('POST', '/api/currency', ['code' => 'USD']);
        $response->assertStatus(200);
        
        $data = $response->json();
        foreach ($data as $item) {
            $this->assertArrayHasKey('code', $item);
            $this->assertArrayHasKey('number', $item);
            $this->assertArrayHasKey('decimal', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('currency_locations', $item);

            // Verifica se currency_locations é um array de objetos ou uma string
            if (is_array($item['currency_locations'])) {
                foreach ($item['currency_locations'] as $location) {
                    $this->assertIsArray($location);
                    $this->assertArrayHasKey('location', $location);
                    $this->assertArrayHasKey('icon', $location);
                }
            } else {
                $this->assertIsString($item['currency_locations']);
            }
        }
    }

    public function testCurrencyRouteWithMultipleValidCodes()
    {
        // Testa com um código de moeda válido
        $response = $this->json('POST', '/api/currency', ['code_list' => ['USD', 'AFN']]);
        $response->assertStatus(200);
        
        $data = $response->json();
        foreach ($data as $item) {
            $this->assertArrayHasKey('code', $item);
            $this->assertArrayHasKey('number', $item);
            $this->assertArrayHasKey('decimal', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('currency_locations', $item);

            // Verifica se currency_locations é um array de objetos ou uma string
            if (is_array($item['currency_locations'])) {
                foreach ($item['currency_locations'] as $location) {
                    $this->assertIsArray($location);
                    $this->assertArrayHasKey('location', $location);
                    $this->assertArrayHasKey('icon', $location);
                }
            } else {
                $this->assertIsString($item['currency_locations']);
            }
        }
    }

    public function testCurrencyRouteWithMultipleValidNumbers()
    {
        // Testa com um código de moeda válido
        $response = $this->json('POST', '/api/currency', ['number_list' => ['978', '971']]);
        $response->assertStatus(200);
        
        $data = $response->json();
        foreach ($data as $item) {
            $this->assertArrayHasKey('code', $item);
            $this->assertArrayHasKey('number', $item);
            $this->assertArrayHasKey('decimal', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('currency_locations', $item);

            // Verifica se currency_locations é um array de objetos ou uma string
            if (is_array($item['currency_locations'])) {
                foreach ($item['currency_locations'] as $location) {
                    $this->assertIsArray($location);
                    $this->assertArrayHasKey('location', $location);
                    $this->assertArrayHasKey('icon', $location);
                }
            } else {
                $this->assertIsString($item['currency_locations']);
            }
        }
    }

    public function testCurrencyRouteWithInvalidCode()
    {
        // Testa com um código de moeda inválido
        $response = $this->json('POST', '/api/currency', ['code' => 'invalid_code']);

        $response->assertStatus(422); // Espera um erro de validação
    }

    public function testCurrencyRouteWithEmptyParameters()
    {
        // Testa sem enviar parâmetros
        $response = $this->json('POST', '/api/currency');

        $response->assertStatus(422); // Espera um erro de validação
        $response->assertExactJson([
            'success' => false,
            'message' => 'Validation errors in your request',
            'errors' => [
                'fields' => [
                    'At least one field must be provided.'
                ]
            ]
        ]);
    }

    public function testCurrencyRouteWithValidNumber()
    {
        // Testa com um número de moeda válido
        $response = $this->json('POST', '/api/currency', ['number' => 840]);

        $response->assertStatus(200);
        $data = $response->json();
        foreach ($data as $item) {
            $this->assertArrayHasKey('code', $item);
            $this->assertArrayHasKey('number', $item);
            $this->assertArrayHasKey('decimal', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('currency_locations', $item);

            // Verifica se currency_locations é um array de objetos ou uma string
            if (is_array($item['currency_locations'])) {
                foreach ($item['currency_locations'] as $location) {
                    $this->assertIsArray($location);
                    $this->assertArrayHasKey('location', $location);
                    $this->assertArrayHasKey('icon', $location);
                }
            } else {
                $this->assertIsString($item['currency_locations']);
            }
        }
    }
}
