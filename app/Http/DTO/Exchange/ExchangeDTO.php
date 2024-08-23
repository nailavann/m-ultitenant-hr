<?php

namespace App\Http\DTO\Exchange;

use Spatie\LaravelData\Data;

class ExchangeDTO extends Data
{
    public function __construct(
        public $date,
        public $currency,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['@attributes']['Date'] . ' tarihli veri.',
            CurrencyDTO::fromDataCollection($data['Currency'])
        );
    }

}
