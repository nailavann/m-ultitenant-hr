<?php

namespace App\Http\Controllers\Tenant\Api\ExchangeRate;

use App\Http\Controllers\Controller;
use App\Http\DTO\Exchange\ExchangeDTO;
use Illuminate\Support\Facades\Http;

class ExchangeRateController extends Controller
{
    private string $url = 'https://www.tcmb.gov.tr/kurlar/today.xml';

    public function __invoke()
    {
        try {
            $xml = Http::get($this->url);

            $xml = simplexml_load_string($xml);

            $data = json_decode(json_encode($xml), true);

            return ExchangeDTO::fromArray($data);
        } catch (\Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
}
