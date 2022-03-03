<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Buyer;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Transaction;
use App\Veritrans\Midtrans;
use Illuminate\Http\Request;
// use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\PseudoTypes\False_;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $serverKey = Setting::where('name', 'server-key')->first()->value;
        Midtrans::$serverKey = $serverKey;
        Midtrans::$isProduction = false;
    }
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
        $data = array(
            'clientKey' => Setting::where('name', 'client-key')->first()->value, 
        );
        return view('chekcout.checkout-buyer', $data);
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


    public function token(Request $request) 
    {
        $validate = [
            'email' => $request->email,
            'phone' => $request->phone,
            'name' => $request->name
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            // $data = [
            //     'email' => $validator->errors()->all()[0],
            //     'phone' => $validator->errors()->all()[1],
            //     'name' => $validator->errors()->all(),
            //     'code' => 400
            // ];
            $data = array(
                'code' => 400,
                'error' => $validator->errors()->all() 
            );
            return response()->json($data, 200);
        }

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

            error_log('masuk ke snap token dari ajax');
            $midtrans = new Midtrans;
            
            $total = 0;
            foreach (session('cart') as $id => $details) {
                $total += $details['price'] * $details['quantity'];
                $items[] = array(
                    'id'        => $id,
                    'price'     => $details['price'],
                    'quantity'  => $details['quantity'],
                    'name'      => $details['name']
                );
            }

            $transaction_details = array(
                'order_id'      => $dataPayment['id'],
                'gross_amount'  => $total
            );
    
            // Populate customer's Info
            $customer_details = array(
                'first_name'      => $validate['name'],
                'email'           => $validate['email'],
                'phone'           => $validate['phone']
                );
    
            // Data yang akan dikirim untuk request redirect_url.
            $credit_card['secure'] = true;
            //ser save_card true to enable oneclick or 2click
            //$credit_card['save_card'] = true;
    
            $time = time();
            $custom_expiry = array(
                'start_time' => date("Y-m-d H:i:s O", $time),
                'unit'       => 'hour', 
                'duration'   => 2
            );
            
            $transaction_data = array(
                'transaction_details'=> $transaction_details,
                'item_details'       => $items,
                'customer_details'   => $customer_details,
                'credit_card'        => $credit_card,
                'expiry'             => $custom_expiry
            );
        
            try
            {
                $snap_token = $midtrans->getSnapToken($transaction_data);
                return $snap_token;
            } 
            catch (Exception $e) 
            {   
                return $e->getMessage;
            }
        }else{
            return redirect('/cart');
        }
        
    }

    public function finish(Request $request)
    {
        Session::forget('cart');
        $result = $request->input('result_data');
        $result = json_decode($result, true);

        $data = [
            'status' => $result['status_code']
        ];
        Payment::where('id', $result['order_id'])
                ->update($data);

        // echo $result->status_message . '<br>';
        echo 'RESULT <br><pre>';
        var_dump($result);
        echo '</pre>' ;
    }

    public function notification()
    {
        $midtrans = new Midtrans;
        echo 'test notification handler';
        $json_result = file_get_contents('php://input');
        $result = json_decode($json_result);

        if($result){
            // $notif = $midtrans->status($result->order_id);
        }
        error_log(print_r($result,TRUE));
    }
}
