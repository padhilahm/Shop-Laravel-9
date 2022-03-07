<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Buyer;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Transaction;
use App\Veritrans\Midtrans;
use Illuminate\Http\Request;
use App\Models\ShippingPrice;
// use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
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
            $products = DB::table('products')
                        ->join('transactions', 'products.id', '=', 'transactions.product_id')
                        ->where('transactions.payment_id', '=', $id)
                        ->selectRaw('products.name, transactions.price, transactions.quantity')
                        ->get();
            $buyer = DB::table('buyers')
                        ->join('payments', 'buyers.id', '=', 'payments.buyer_id')
                        ->where('payments.id', '=', $id)
                        ->selectRaw('buyers.name, buyers.phone, buyers.email')
                        ->first();
            $latitude = Setting::where('name', 'latitude')->first()->value;
            $longitude = Setting::where('name', 'longitude')->first()->value;
            $shop = array(
                'address' => Setting::where('name', 'address')->first()->value, 
                'maps' => "https://www.google.com/maps/search/?api=1&query=$latitude%2C$longitude"
            );

            $data = array(
                'code' => 200,
                'dataPayment' => $payment,
                'dataBuyer' => $buyer,
                'dataProducts' => $products,
                'dataShop' => $shop,
                'error' => ''
            );
            return response()->json($data, 200);
        }
    }

    public function checkoutBuyer()
    {
        // $distance = $this->distance(-3.448323, 114.871273, -3.443305, 114.84782);
        session()->forget('overStock');
        $data = array(
            'clientKey' => Setting::where('name', 'client-key')->first()->value,
            'latitude' => Setting::where('name', 'latitude')->first()->value,
            'longitude' => Setting::where('name', 'longitude')->first()->value,
            'shippingMax' => Setting::where('name', 'shipping-max')->first()->value,
            'shippingPrices' => ShippingPrice::orderBy('distince')->get()
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
    
    public function distancePost(Request $request)
    {
        $latitudeFrom = $request->latitudeFrom;
        $longitudeFrom = $request->longitudeFrom;
        $latitudeTo = $request->latitudeTo;
        $longitudeTo = $request->longitudeTo;
        $earthRadius = 6371000;

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
        $distance = ($angle * $earthRadius) / 1000;

        $shippingMax = Setting::where('name', 'shipping-max')->first()->value;
        $shippingPrices = ShippingPrice::orderBy('distince')->get();

        if ($distance > $shippingMax) {
            $data = array(
                'code' => 400,
                'priceShipping' => 0,
                'message' => 'Mohon maaf lokasi Anda masih belum terjangkau untuk pengantaran secara langsung'
            );
            return response()->json($data, 200);
        }

        // menghitung harga per jarak ditentukan
        if ($distance >= $shippingPrices[0]->distince && $distance <= $shippingPrices[1]->distince) {
            $shipping = $shippingPrices[0]->price;
        }
        for ($i = 1 ; $i < $shippingPrices->count(); $i++){
            if ($i == $shippingPrices->count()-1) {
                if ($distance > $shippingPrices[$i]->distince){
                    $shipping = $shippingPrices[$i]->price;
                }
            }else{
                if ($distance > $shippingPrices[$i]->distince && $distance <= $shippingPrices[$i+1]->distince){
                    $shipping = $shippingPrices[$i]->price;
                }
            }
        }

        $data = array(
            'code' => 200,
            'priceShipping' => $shipping,
            'message' => 'Biaya pengantaran untuk lokasi Anda Rp.'.$shipping,
        );
        return response()->json($data, 200);
    }

    public function token(Request $request)
    {
        $validate = [
            'email' => $request->email,
            'phone' => $request->phone,
            'name' => $request->name
        ];

        if ($request->shippingType == 1) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'phone' => 'required|numeric',
                'name' => 'required',
                'address' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'shippingType' => 'required'
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'phone' => 'required|numeric',
                'name' => 'required',
                'shippingType' => 'required'
            ]);
        }

        if ($request->shippingType != 1 and $request->shippingType != 2) {
            $data = array(
                'code' => 400,
                'error' => ''
            );
            return response()->json($data, 200);
        }
        
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

        // cek stock
        foreach (session('cart') as $id => $details) {
            $productStock = Product::find($id)->stock;
            if ($productStock < $details['quantity']) {
                $data = array(
                    'code' => 400,
                    'error' => 'cart'
                );
                session()->put('overStock', 'Jumlah pembelian '.$details['name'].' melebihi stok yang ada, stok tersedia '.$productStock);
                return response()->json($data, 200);
            }
        }
        
        if($request->shippingType == 1){
            $latitudeTo = Setting::where('name', 'latitude')->first()->value;
            $longitudeTo = Setting::where('name', 'longitude')->first()->value;
            $latitudeFrom = $request->latitude;
            $longitudeFrom = $request->longitude;
            $distance = $this->distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)/1000;

            $shippingMax = Setting::where('name', 'shipping-max')->first()->value;
            $shippingPrices = ShippingPrice::orderBy('distince')->get();

            if ($distance > $shippingMax) {
                $data = array(
                    'code' => 400,
                    'error' => 'over'
                );
                return response()->json($data, 200);
            }
            
            // menghitung harga per jarak ditentukan
            if ($distance >= $shippingPrices[0]->distince && $distance <= $shippingPrices[1]->distince) {
                $shipping = $shippingPrices[0]->price;
            }
            for ($i = 1 ; $i < $shippingPrices->count(); $i++){
                if ($i == $shippingPrices->count()-1) {
                    if ($distance > $shippingPrices[$i]->distince){
                        $shipping = $shippingPrices[$i]->price;
                    }
                }else{
                    if ($distance > $shippingPrices[$i]->distince && $distance <= $shippingPrices[$i+1]->distince){
                        $shipping = $shippingPrices[$i]->price;
                    }
                }
            }

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

        if ($request->shippingType == 1) {
            $total += $shipping;
            $items[] = array(
                'id'        => 'shipping',
                'price'     => $shipping,
                'quantity'  => 1,
                'name'      => 'Ongkir'
            );
        }

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
                
                if ($request->shippingType == 1) {
                    $dataPayment = array(
                        'id' => $paymentId,
                        'buyer_id' => $buyer->id,
                        'shipping_type_id' => $request->shippingType,
                        'token' => $snap_token,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude,
                        'shipping' => $shipping,
                        'address' => $request->address
                    );
                }else{
                    $dataPayment = array(
                        'id' => $paymentId,
                        'buyer_id' => $buyer->id,
                        'shipping_type_id' => $request->shippingType,
                        'token' => $snap_token,
                    );
                }

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

                    Product::where('id', $id)
                            ->decrement('stock', $details['quantity']);
                }
                Transaction::insert($dataTransaction);
            } else {
                Buyer::create($validate);
                $buyer = Buyer::where('email', $validate['email'])
                    ->where('phone', $validate['phone'])
                    ->first();
                if ($request->shippingType == 1) {
                    $dataPayment = array(
                        'id' => $paymentId,
                        'buyer_id' => $buyer->id,
                        'shipping_type_id' => $request->shippingType,
                        'token' => $snap_token,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude,
                        'shipping' => $shipping,
                        'address' => $request->address
                    );
                }else{
                    $dataPayment = array(
                        'id' => $paymentId,
                        'buyer_id' => $buyer->id,
                        'shipping_type_id' => $request->shippingType,
                        'token' => $snap_token,
                    );
                }
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

                    Product::where('id', $id)
                            ->decrement('stock', $details['quantity']);
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
        session()->forget('cart');
        $result = $request->input('result_data');
        $result = json_decode($result, true);

        $data = [
            'status' => $result['status_code']
        ];
        Payment::where('id', $result['order_id'])
            ->update($data);

        return redirect('/transaction-check?no=' . $result['order_id']);

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
