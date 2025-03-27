<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        return CountryResource::collection(Country::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|size:2|unique:countries',
            'name' => 'required|string|max:255|unique:countries'
        ]);

        $country = Country::create($validated);

        return new CountryResource($country, 201);
    }

    public function show(Country $country)
    {
        return new CountryResource($country);
    }

    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'code' => 'sometimes|string|size:2|unique:countries,code,'.$country->id,
            'name' => 'sometimes|string|max:255|unique:countries,name,'.$country->id
        ]);

        $country->update($validated);

        return new CountryResource($country);
    }

    public function destroy(Country $country)
    {
        $country->delete();
        return response()->json(null, 204);
    }
}
