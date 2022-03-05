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

    public function checkTransaction()
    {
        $data = array(
            'clientKey' => Setting::where('name', 'client-key')->first()->value,
        );
        return view('chekcout.check-transaction', $data);
    }

    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'paymentId' => 'required'
        ]);
        $id = $request->paymentId;

        if ($validator->fails()) {
            $data = array(
                'code' => 400,
                'error' => $validator->errors()->all()
            );
            return response()->json($data, 200);
        } else {
            $payment = Payment::find($id);

            $data = array(
                'code' => 200,
                'data' => $payment,
                'error' => ''
            );
            return response()->json($data, 200);
        }
    }

    public function checkoutBuyer()
    {
        // $distance = $this->distance(-3.448323, 114.871273, -3.443305, 114.84782);
        $data = array(
            'clientKey' => Setting::where('name', 'client-key')->first()->value
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

    public function distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
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
            'name' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        if ($validator->fails()) {
            $data = array(
                'code' => 400,
                'error' => $validator->errors()->all()
            );
            return response()->json($data, 200);
        }

        // Session::forget('cart');
        if (session('cart') == null) {
            $data = array(
                'code' => 400,
                'error' => 'cart'
            );
            return response()->json($data, 200);
            // return redirect('/cart');
        }
        
        $latitudeTo = Setting::where('name', 'latitude')->first()->value;
        $longitudeTo = Setting::where('name', 'longitude')->first()->value;
        $latitudeFrom = $request->latitude;
        $longitudeFrom = $request->longitude;
        $distance = $this->distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)/1000;
        if ($distance > 20) {
            $data = array(
                'code' => 400,
                'error' => 'over'
            );
            return response()->json($data, 200);
        }

        if ($distance >= 15) {
            $shipping = 15000;
        }elseif ($distance > 10) {
            $shipping = 10000;
        }elseif($distance > 5){
            $shipping = 5000;
        }else {
            $shipping = 0;
        }

        // error_log('masuk ke snap token dari ajax');
        $paymentId = rand();
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

        $total += $shipping;

        $items[] = array(
            'id'        => 'shipping',
            'price'     => $shipping,
            'quantity'  => 1,
            'name'      => 'Ongkir'
        );

        $transaction_details = array(
            'order_id'      => $paymentId,
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
            'transaction_details' => $transaction_details,
            'item_details'       => $items,
            'customer_details'   => $customer_details,
            'credit_card'        => $credit_card,
            'expiry'             => $custom_expiry
        );

        try {
            $snap_token = $midtrans->getSnapToken($transaction_data);

            $buyer = Buyer::where('email', $request->email)
                ->where('phone', $request->phone)
                ->first();

            if ($buyer) {
                Buyer::where('id', $buyer->id)
                    ->update($validate);

                $dataPayment = array(
                    'id' => $paymentId,
                    'buyer_id' => $buyer->id,
                    'token' => $snap_token,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'shipping' => $shipping,
                    'address' => $request->address
                );
                Payment::create($dataPayment);
                foreach (session('cart') as $id => $details) {
                    $dataTransaction[] = array(
                        'product_id' => $id,
                        'payment_id' => $paymentId,
                        'price' => $details['price'],
                        'quantity' => $details['quantity'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                }
                Transaction::insert($dataTransaction);
            } else {
                Buyer::create($validate);
                $buyer = Buyer::where('email', $validate['email'])
                    ->where('phone', $validate['phone'])
                    ->first();
                $dataPayment = array(
                    'id' => $paymentId,
                    'buyer_id' => $buyer->id,
                    'token' => $snap_token,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'shipping' => $shipping,
                    'address' => $request->address
                );
                Payment::create($dataPayment);
                foreach (session('cart') as $id => $details) {
                    $dataTransaction[] = array(
                        'product_id' => $id,
                        'payment_id' => $paymentId,
                        'price' => $details['price'],
                        'quantity' => $details['quantity'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                }
                Transaction::insert($dataTransaction);
            }

            return $snap_token;
        } catch (Exception $e) {
            return $e->getMessage;
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

        return redirect('/transaction-check?no=' . $result['order_id']);

        // echo $result->status_message . '<br>';
        // echo  $result['order_id'];
        // echo 'RESULT <br><pre>';
        // var_dump($result);
        // echo '</pre>' ;
    }

    public function notification()
    {
        $midtrans = new Midtrans;
        echo 'test notification handler';
        $json_result = file_get_contents('php://input');
        $result = json_decode($json_result);

        if ($result) {
            // $notif = $midtrans->status($result->order_id);
        }
        error_log(print_r($result, TRUE));
    }
}
