<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCurrencyRequest;
use App\Services\CurrencyClientService;
use App\Services\CurrencyParserService;
use Illuminate\Support\Facades\Cache;

class CurrencyController extends Controller {
    private $clientService;
    private $parserService;

    public function __construct(CurrencyClientService $clientService, CurrencyParserService $parserService) {
        $this->clientService = $clientService;
        $this->parserService = $parserService;
    }

    public function show(GetCurrencyRequest $request) {
        $validated = $request->validated();
        $cacheKey = $this->generateCacheKey($validated);
        
        $results = Cache::remember($cacheKey, 60 * 60 * 12, function () use ($validated) {
            $html = $this->clientService->fetchCurrencyHtml();
            return $this->parserService->extractCurrencyData($html, $validated);
        });

        return response()->json($results, $results == [] ? 422 : 200);
    }

    private function generateCacheKey($validated) {
        return 'currency:' . md5(json_encode($validated));
    }
}
