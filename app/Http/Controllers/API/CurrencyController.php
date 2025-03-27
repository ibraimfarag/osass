<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencies = Currency::all();
        return CurrencyResource::collection($currencies);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|size:3|unique:currencies',
            'name' => 'required|string|max:255',
            'exchange_rate' => 'required|numeric|min:0',
            'is_base' => 'sometimes|boolean'
        ]);

        if ($validated['is_base'] ?? false) {
            Currency::where('is_base', true)->update(['is_base' => false]);
        }

        $currency = Currency::create($validated);

        return new CurrencyResource($currency);
    }

    public function update(Request $request, Currency $currency)
    {
        $validated = $request->validate([
            'code' => 'sometimes|string|size:3|unique:currencies,code,'.$currency->id,
            'name' => 'sometimes|string|max:255',
            'exchange_rate' => 'sometimes|numeric|min:0',
            'is_base' => 'sometimes|boolean'
        ]);

        if ($request->has('is_base') && $request->is_base) {
            Currency::where('is_base', true)->update(['is_base' => false]);
        }

        $currency->update($validated);

        return new CurrencyResource($currency);
    }

    public function destroy(Currency $currency)
    {
        $currency->delete();
        return response()->json(null, 204);
    }

    }
