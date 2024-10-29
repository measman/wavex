<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCurrrencyRequest;
use App\Http\Requests\UpdateCurrrencyRequest;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CurrencyController extends Controller
{
    public function index(){
        $currencies = CurrencyResource::collection(Currency::all());
        return Inertia::render('Admin/Currencies/index', ['currencies'=>$currencies]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Currencies/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCurrrencyRequest $request)
    {
        $validatedData = $request->validated();        
        Currency::create($validatedData);
        return  redirect()->route('currencies.index');        
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency)
    {
        return Inertia::render('Admin/Currencies/Edit', [
            'currency'=>CurrencyResource::make($currency)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurrrencyRequest $request, Currency $currency)
    {
        $currency->update($request->validated());
        return redirect()->route('currencies.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}