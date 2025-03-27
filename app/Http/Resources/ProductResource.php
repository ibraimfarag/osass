<?php

namespace App\Http\Resources;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // app/Http/Resources/ProductResource.php

    public function toArray($request)
    {
        $baseCurrency = Currency::where('is_base', true)->first();
        $targetCurrencyCode = $request->input('currency_code');

        $conversionRate = null;
        if ($targetCurrencyCode && $baseCurrency) {
            $targetCurrency = Currency::where('code', $targetCurrencyCode)->first();
            if ($targetCurrency) {
                $conversionRate = number_format(
                    $targetCurrency->exchange_rate / $baseCurrency->exchange_rate,
                    2,
                    '.',
                    ''
                );
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'base_price' => $this->base_price,
            'currency' => $targetCurrencyCode ?? $baseCurrency?->code,
            'applicable_price' => $this->when(
                $conversionRate !== null,
                function () use ($conversionRate) {
                    return number_format(
                        $this->base_price * $conversionRate,
                        2,
                        '.',
                        ''
                    );
                }
            ),
            'conversion_rate' => $conversionRate
        ];
    }
}
