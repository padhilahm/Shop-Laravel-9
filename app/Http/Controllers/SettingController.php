<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array(
            'url' => 'setting', 
            'settings' => Setting::all()
        );
        return view('setting.index', $data);
    }
    
    public function location()
    {
        $data = array(
            'url' => 'setting',
            'latitude' => Setting::where('name', 'latitude')->first(),
            'longitude' => Setting::where('name', 'longitude')->first(),
        );
        return view('setting.location', $data);
    }
    
    public function payment()
    {
        $data = array(
            'url' => 'setting', 
            'clientKey' => Setting::where('name', 'client-key')->first(),
            'serverKey' => Setting::where('name', 'server-key')->first()
        );
        return view('setting.payment', $data);
    }
    
    public function shop()
    {
        $data = array(
            'url' => 'setting', 
            'shopName' => Setting::where('name', 'shop-name')->first(),
            'address' => Setting::where('name', 'address')->first()
        );
        return view('setting.shop', $data);
    }
    
    public function email()
    {
        $data = array(
            'url' => 'setting', 
            'email' => Setting::where('name', 'email')->first(),
            'password' => Setting::where('name', 'password')->first(),
            'smtpHost' => Setting::where('name', 'smtp-host')->first(),
            'smtpPort' => Setting::where('name', 'smtp-port')->first(),
        );
        return view('setting.email', $data);
    }
    
    public function delivery()
    {
        $data = array(
            'url' => 'setting', 
            'delivered' => Setting::where('name', 'delivered')->first(),
            'take' => Setting::where('name', 'take')->first(),
        );
        return view('setting.delivery', $data);
    }
    
    public function paymentType()
    {
        $data = array(
            'url' => 'setting', 
            'direct' => Setting::where('name', 'direct')->first(),
            'cod' => Setting::where('name', 'cod')->first(),
        );
        return view('setting.payment-type', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSettingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSettingRequest  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function updateLocation(Request $request)
    {
        $validate = $request->validate([
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        $data[] = [
            'value' => $validate['latitude'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $data[] = [
            'value' => $validate['longitude'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        Setting::where('name', 'latitude')
                    ->update($data[0]);
        Setting::where('name', 'longitude')
                    ->update($data[1]);
        
        return redirect('setting-location')->with('success', 'Settings has been saved');
    }
    
    public function updatePayment(Request $request)
    {
        $validate = $request->validate([
            'serverKey' => 'required',
            'clientKey' => 'required'
        ]);
        
        $data[] = [
            'value' => $validate['clientKey'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $data[] = [
            'value' => $validate['serverKey'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        Setting::where('name', 'client-key')
                    ->update($data[0]);
        Setting::where('name', 'server-key')
                    ->update($data[1]);
        
        return redirect('setting-payment')->with('success', 'Settings has been saved');
    }
    
    public function updateShop(Request $request)
    {
        $validate = $request->validate([
            'shopName' => 'required',
            'address' => 'required'
        ]);
        
        $data[] = [
            'value' => $validate['shopName'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $data[] = [
            'value' => $validate['address'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        Setting::where('name', 'shop-name')
                    ->update($data[0]);
        Setting::where('name', 'address')
                    ->update($data[1]);
        
        return redirect('setting-shop')->with('success', 'Settings has been saved');
    }
    
    public function updateEmail(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required',
            'password' => 'required',
            'smtpHost' => 'required',
            'smtpPort' => 'required',
        ]);
        
        $data[] = [
            'value' => $validate['email'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $data[] = [
            'value' => $validate['password'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $data[] = [
            'value' => $validate['smtpHost'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $data[] = [
            'value' => $validate['smtpPort'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        Setting::where('name', 'email')
                    ->update($data[0]);
        Setting::where('name', 'password')
                    ->update($data[1]);
        Setting::where('name', 'smtp-host')
                    ->update($data[2]);
        Setting::where('name', 'smtp-port')
                    ->update($data[3]);
        
        return redirect('setting-email')->with('success', 'Settings has been saved');
    }

    public function updateDelivery(Request $request)
    {
        $validate = $request->validate([
            'delivered' => 'required',
            'take' => 'required'
        ]);
        
        $data[] = [
            'value' => $validate['delivered'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $data[] = [
            'value' => $validate['take'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        Setting::where('name', 'delivered')
                    ->update($data[0]);
        Setting::where('name', 'take')
                    ->update($data[1]);
        
        return redirect('setting-delivery')->with('success', 'Settings has been saved');
    }
    
    public function updatePaymentType(Request $request)
    {
        $validate = $request->validate([
            'direct' => 'required',
            'cod' => 'required'
        ]);
        
        $data[] = [
            'value' => $validate['direct'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $data[] = [
            'value' => $validate['cod'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        Setting::where('name', 'direct')
                    ->update($data[0]);
        Setting::where('name', 'cod')
                    ->update($data[1]);
        
        return redirect('setting-payment-type')->with('success', 'Settings has been saved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
