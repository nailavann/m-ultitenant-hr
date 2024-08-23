<?php

namespace App\Http\DTO\Exchange;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class CurrencyDTO extends Data
{
    public function __construct(
        public $name,
        public $code,
        public $buying
    )
    {
    }

    public static function fromDataArray(array $data): self
    {
        return new self(
            $data['Isim'],
            $data['@attributes']['Kod'],
            !is_array($data['BanknoteBuying']) ? number_format((float)$data['BanknoteBuying'], 2) . ' ₺' : null
        );
    }

    public static function fromDataCollection($data): Collection
    {
        return collect($data)->map(fn($item) => self::fromDataArray($item));
    }
}

