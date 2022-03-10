<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePaymentRequest;
use Symfony\Component\Console\Input\Input;
use App\Http\Requests\UpdatePaymentRequest;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $no = 5;
        if (request('search')) {
            $search = request('search');
            $payments = Payment::where('id', 'like', "%$search%")
                            ->orderByDesc('created_at')
                            ->paginate($no);
        }else{
            $payments = Payment::orderByDesc('created_at')
                            ->paginate($no);
        }

        $data = array(
            'url' => 'payments',
            'payments' => $payments, 
            'no' => $no,
            'clientKey' => Setting::where('name', 'client-key')->first()->value,
        );
        return view('payments.index', $data);
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
     * @param  \App\Http\Requests\StorePaymentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        $buyer = Buyer::findOrFail($payment->buyer_id);
        $products = DB::table('products')
                        ->join('transactions', 'products.id', '=', 'transactions.product_id')
                        ->where('transactions.payment_id', '=', $payment->id)
                        ->selectRaw('products.name, transactions.price, transactions.quantity')
                        ->get();
        $data = array(
            'url' => 'payments',
            'payment' => $payment,
            'buyer' => $buyer,
            'products' => $products,
            'clientKey' => Setting::where('name', 'client-key')->first()->value
        );
        return view('payments.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePaymentRequest  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        Transaction::where('payment_id', $payment->id)
                    ->delete();
        Payment::destroy($payment->id);
        return redirect('payments')->with('success', 'Payment has been deleted');
    }
}
