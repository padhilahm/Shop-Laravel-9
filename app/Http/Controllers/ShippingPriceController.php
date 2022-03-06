<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\ShippingPrice;

class ShippingPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array(
            'url' => 'shipping-price', 
            'shippingPrices' => ShippingPrice::orderBy('distince')->get(),
            'shippingMax' => Setting::where('name', 'shipping-max')->first()->value,
        );
        return view('shipping-price.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array(
            'url' => 'shipping-price',
        );
        return view('shipping-price.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'distince' => 'required|numeric',
            'price' => 'required|numeric'
        ]);
        ShippingPrice::create($validate);
        return redirect('/shipping-price')->with('success', 'Shipping price has been created');
    }
    
    public function updateShippingMax(Request $request)
    {
        $shippingMax = $request->shippingMax;
        $data = [
            'value' => $shippingMax
        ];
        Setting::where('name', 'shipping-max')
                ->update($data);
        return redirect('/shipping-price');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShippingPrice  $shippingPrice
     * @return \Illuminate\Http\Response
     */
    public function show(ShippingPrice $shippingPrice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShippingPrice  $shippingPrice
     * @return \Illuminate\Http\Response
     */
    public function edit(ShippingPrice $shippingPrice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShippingPrice  $shippingPrice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShippingPrice $shippingPrice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShippingPrice  $shippingPrice
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShippingPrice $shippingPrice)
    {
        //
    }
}
