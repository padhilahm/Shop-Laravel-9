<?php

namespace App\Http\Controllers;

use App\Models\Product;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
// use App\Http\Requests\StoreProductRequest;
// use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array(
            'products' => Product::orderByDesc('created_at')
                                ->paginate(8), 
        );
        return view('home.index', $data);
        // $products = Product::all();
        // return view('products', compact('products'));
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

    public function cart()
    {
        return view('product.cart');
        // return view('cart');
        // return 'cart';
    }
    
    public function checkout()
    {
        return view('product.checkout');
    }

    public function addToCart(Request $request)
    {
        session()->forget('overStock');

        $id = $request->id;
        $quantity = $request->quantity;

        $product = Product::findOrFail($id);

        // cek stok dibeli melebih stok yg ada
        if ($quantity > $product->stock) {
            $data = array(
                'code' => 400,
                'message' => "Jumlah yang dibeli melebihi stok"
            );
            return response()->json($data, 200);
        }

        // cek stok yg dibeli melebih stok yg ada dan stok di cart
        if (isset(session('cart')[$id])) {
            if ($quantity > ($product->stock - session('cart')[$id]['quantity']) ) {
                $data = array(
                    'code' => 400,
                    'message' => "Jumlah yang dibeli melebihi stok produk dan jumlah di Keranjang"
                );
                return response()->json($data, 200);
            }
        }
          
        $cart = session()->get('cart', []);
  
        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image" => $product->image
            ];
        }
        session()->put('cart', $cart);

        $data = array(
            'code' => 200,
            'totalCart' => count((array) session('cart')), 
            'quantity' => $quantity,
            'message' => "Produk berhasil ditambahkan ke keranjang"
        );
        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(StoreProductRequest $request)
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $data = array(
            'product' => $product, 
        );
        return view('product.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        session()->forget('overStock');

        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            // session()->flash('success', 'Cart updated successfully');
        }
        $total = 0;
        if (session('cart')) {
            foreach (session('cart') as $id => $details) {
                $image = $details['image'];
                if ($image) {
                    $image = "/storage/".$details['image'];
                }else{
                    $image = 'https://dummyimage.com/100x100/dee2e6/6c757d.jpg';
                }
                $total += $details['price'] * $details['quantity'];
                $dataView[] = [
                    'id' => $id,
                    'name' => $details['name'],
                    'price' => $details['price'],
                    'quantity' => $details['quantity'],
                    'image' => $image
                ];
            }
           
            $data = [
                'dataView' => $dataView,
                'total' => $total
            ];
        }else{
            $data = [
                'dataView' => '',
                'total' => $total
            ];
        }
        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function remove(Request $request)
    {
        session()->forget('overStock');

        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            // session()->flash('success', 'Product removed successfully');
        }
        $total = 0;
        if (session('cart')) {
            foreach (session('cart') as $id => $details) {
                $image = $details['image'];
                if ($image) {
                    $image = "/storage/".$details['image'];
                }else{
                    $image = 'https://dummyimage.com/100x100/dee2e6/6c757d.jpg';
                }
                $total += $details['price'] * $details['quantity'];
                $dataView[] = [
                    'id' => $id,
                    'name' => $details['name'],
                    'price' => $details['price'],
                    'quantity' => $details['quantity'],
                    'image' => $image
                ];
            }
           
            $data = [
                'dataView' => $dataView,
                'total' => $total,
                'totalCart' => count((array) session('cart'))
            ];
        }else{
            $data = [
                'dataView' => '',
                'total' => $total,
                'totalCart' => count((array) session('cart'))
            ];
        }
        return response()->json($data, 200);
    }
}
