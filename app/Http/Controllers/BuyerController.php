<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $buyers = Buyer::where('email', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
                            ->orWhere('phone', 'like', "%$search%")
                            ->orderByDesc('created_at')
                            ->paginate();
        }else{
            $buyers = Buyer::orderByDesc('created_at')
            ->paginate();
        }
        
        $data = array(
            'url' => 'buyers', 
            'buyers' => $buyers
        );
        return view('buyers.index', $data);
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
     * @param  \App\Http\Requests\StoreBuyerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function edit(Buyer $buyer)
    {
        $data = array(
            'url' => 'buyers', 
            'buyer' => $buyer 
        );
        return view('buyers.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBuyerRequest  $request
     * @param  \App\Models\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Buyer $buyer)
    {
        $validate = $request->validate([
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'name' => 'required'
        ]);
        Buyer::where('id', $buyer->id)
                ->update($validate);
        return redirect('/buyers')->with('success', 'Buyer has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Buyer $buyer)
    {
        Buyer::destroy($buyer->id);
        return redirect('buyers')->with('success', 'Buyer has been deleted');

    }
}
