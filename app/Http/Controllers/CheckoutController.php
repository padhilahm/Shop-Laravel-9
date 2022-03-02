<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\PseudoTypes\False_;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function checkoutBuyer()
    {
        return view('chekcout.checkout-buyer');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function storeBuyer(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'name' => 'required'
        ]);
        // Session::forget('cart');
        if (session('cart')) {
            $buyer = Buyer::where('email', $request->email)
                ->where('phone', $request->phone)
                ->first();
    
            if ($buyer) {
                $dataPayment = array(
                    'id' => rand(),
                    'buyer_id' => $buyer->id
                );
                Payment::create($dataPayment);
                foreach (session('cart') as $id => $details) {
                    $dataTransaction[] = array(
                        'product_id' => $id,
                        'payment_id' => $dataPayment['id'],
                        'price' => $details['price'],
                        'quantity' => $details['quantity'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                }
                Transaction::insert($dataTransaction);
            } else {
                Buyer::create($validate);
                $buyer = Buyer::where('email', $request->email)
                    ->where('phone', $request->phone)
                    ->first();
                $dataPayment = array(
                    'id' => rand(),
                    'buyer_id' => $buyer->id
                );
                Payment::create($dataPayment);
                foreach (session('cart') as $id => $details) {
                    $dataTransaction[] = array(
                        'product_id' => $id,
                        'payment_id' => $dataPayment['id'],
                        'price' => $details['price'],
                        'quantity' => $details['quantity'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                }
                Transaction::insert($dataTransaction);
            }
            // Session::forget('cart');
            // $status = TRUE;
            $data = array(
                'buyer' => $buyer,
            );
            return view('chekcout.index', $data);
        }else{
            // $status = FALSE;
            return redirect('/cart');
        }
        // Session::forget('cart');
        // if ($status) {
        //     $data = array(
        //         'buyer' => $buyer,
        //     );
        //     return view('chekcout.index', $data);
        // }else{
        //     return redirect('/cart');
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
