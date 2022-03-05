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
            'setting' => Setting::exists(),
            'clientKey' => Setting::where('name', 'client-key')->first(),
            'serverKey' => Setting::where('name', 'server-key')->first(),
            'latitude' => Setting::where('name', 'latitude')->first(),
            'longitude' => Setting::where('name', 'longitude')->first(),
        );
        return view('setting.index', $data);
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
        $validate = $request->validate([
            'serverKey' => 'required',
            'clientKey' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        $data[] = [
            'name' => 'client-key',
            'value' => $validate['clientKey'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $data[] = [
            'name' => 'server-key',
            'value' => $validate['serverKey'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        Setting::insert($data);
        return redirect('setting')->with('success', 'Settings has been saved');
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
    public function updateSetting(Request $request)
    {
        $validate = $request->validate([
            'serverKey' => 'required',
            'clientKey' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
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

        Setting::where('name', 'client-key')
                    ->update($data[0]);
        Setting::where('name', 'server-key')
                    ->update($data[1]);
        Setting::where('name', 'latitude')
                    ->update($data[2]);
        Setting::where('name', 'longitude')
                    ->update($data[3]);
        
        return redirect('setting')->with('success', 'Settings has been saved');
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
