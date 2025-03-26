<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $validator = validator($request->all(), [
            'country_code' => 'nullable|string|size:2',
            'currency_code' => 'nullable|string|size:3',
            'date' => 'nullable|date_format:Y-m-d',
            'order' => 'nullable|in:lowest-to-highest,highest-to-lowest'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters',
                'errors' => $validator->errors()
            ], 422);
        }

        $countryCode = $request->input('country_code');
        $currencyCode = $request->input('currency_code');
        $date = $request->input('date') ?? now()->format('Y-m-d');
        $order = $request->input('order');

        $products = Product::with(['priceLists' => function($query) use ($countryCode, $currencyCode, $date) {
            $query->where(function($q) use ($countryCode) {
                $q->whereNull('country_code')
                  ->when($countryCode, function($q) use ($countryCode) {
                      $q->orWhere('country_code', $countryCode);
                  });
            })
            ->where(function($q) use ($currencyCode) {
                $q->whereNull('currency_code')
                  ->when($currencyCode, function($q) use ($currencyCode) {
                      $q->orWhere('currency_code', $currencyCode);
                  });
            })
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->orderBy('priority');
        }])->get();

        $products->each(function ($product) {
            $product->setAttribute('applicable_price',
                $product->priceLists->isNotEmpty()
                    ? $product->priceLists->first()->price
                    : $product->base_price
            );
        });

        if ($order) {
            $sorted = $order === 'lowest-to-highest'
                ? $products->sortBy('applicable_price')
                : $products->sortByDesc('applicable_price');

            $products = $sorted->values();
        }

        return ProductResource::collection($products);
    }


    public function show(Request $request, $id)
    {
        $validator = validator($request->all(), [
            'country_code' => 'nullable|string|size:2',
            'currency_code' => 'nullable|string|size:3',
            'date' => 'nullable|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid parameters',
                'errors' => $validator->errors()
            ], 422);
        }

        $countryCode = $request->input('country_code');
        $currencyCode = $request->input('currency_code');
        $date = $request->input('date') ?? now()->format('Y-m-d');

        $product = Product::with(['priceLists' => function($query) use ($countryCode, $currencyCode, $date) {
            $query->where(function($q) use ($countryCode) {
                $q->whereNull('country_code')
                  ->when($countryCode, function($q) use ($countryCode) {
                      $q->orWhere('country_code', $countryCode);
                  });
            })
            ->where(function($q) use ($currencyCode) {
                $q->whereNull('currency_code')
                  ->when($currencyCode, function($q) use ($currencyCode) {
                      $q->orWhere('currency_code', $currencyCode);
                  });
            })
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->orderBy('priority');
        }])->findOrFail($id);


        $product->setAttribute('applicable_price',
            $product->priceLists->isNotEmpty()
                ? $product->priceLists->first()->price
                : $product->base_price
        );


        return (new ProductResource($product))
            ->additional([
                'meta' => [
                    'price_calculation_date' => $date,
                    'currency' => $currencyCode,
                    'country' => $countryCode
                ]
            ]);
    }

}
