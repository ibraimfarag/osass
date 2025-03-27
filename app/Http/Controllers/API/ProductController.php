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



        if ($order) {
            $sorted = $order === 'lowest-to-highest'
                ? $products->sortBy('base_price')
                : $products->sortByDesc('base_price');

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




        return (new ProductResource($product))
            ->additional([
                'meta' => [
                    'price_calculation_date' => $date,
                    'currency' => $currencyCode,
                    'country' => $countryCode
                ]
            ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $product = Product::create($validated);

        return new ProductResource($product);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'base_price' => 'sometimes|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $product->update($validated);

        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }

}
