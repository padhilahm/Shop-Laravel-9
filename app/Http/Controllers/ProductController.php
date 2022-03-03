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
            'products' => Product::orderBy('created_at', 'desc')
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
        $id = $request->id;
        $quantity = $request->quantity;

        $product = Product::findOrFail($id);
          
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
        // return redirect()->back()->with('success', 'Product added to cart successfully!');
        // return count((array) session('cart'));
        $data = array(
            'totalCart' => count((array) session('cart')), 
            'quantity' => $quantity,
            'alert' => "Product added to cart successfully!"
            // 'alert' => "<div class='alert alert-success'>Product added to cart successfully!</div>"
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
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            // session()->flash('success', 'Cart updated successfully');
        }
        $total = 0;
        if (session('cart')) {
            foreach (session('cart') as $id => $details) {
                $total += $details['price'] * $details['quantity'];
    
                if ($details['image']) {
                    $image = "<img src='/storage/".$details['image']."' width='100' height='100' class='img-responsive' />";
                }else{
                    $image = "<img src='https://dummyimage.com/100x100/dee2e6/6c757d.jpg' width='100' height='100' class='img-responsive' />";
                }
                $dataView[] = "<tr data-id='$id'>
                <td data-th='Product'>
                    <div class='row'>
                        <div class='col-sm-3 hidden-xs'>
                            $image
                        </div>
                        <div class='col-sm-9'>
                            <h4 class='nomargin'>".$details['name']."</h4>
                        </div>
                    </div>
                </td>
                <td data-th='Price'>Rp.".$details['price']."</td>
                <td data-th='Quantity'>
                    <input type='number' value='".$details['quantity']."'
                        class='form-control quantity' id='quantity$id' onchange='updateCart($id)' />
                </td>
                <td data-th='Subtotal' class='text-center'>Rp.".$details['price'] * $details['quantity']."</td>
                <td class='actions' data-th=''>
                    <button class='btn btn-danger btn-sm remove-from-cart' onclick='deleteCart($id)'><i class='fa fa-trash-o'></i></button>
                </td>
                </tr>";
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
                $total += $details['price'] * $details['quantity'];
    
                if ($details['image']) {
                    $image = "<img src='/storage/".$details['image']."' width='100' height='100' class='img-responsive' />";
                }else{
                    $image = "<img src='https://dummyimage.com/100x100/dee2e6/6c757d.jpg' width='100' height='100' class='img-responsive' />";
                }
                $dataView[] = "<tr data-id='$id'>
                <td data-th='Product'>
                    <div class='row'>
                        <div class='col-sm-3 hidden-xs'>
                            $image
                        </div>
                        <div class='col-sm-9'>
                            <h4 class='nomargin'>".$details['name']."</h4>
                        </div>
                    </div>
                </td>
                <td data-th='Price'>Rp.".$details['price']."</td>
                <td data-th='Quantity'>
                    <input type='number' value='".$details['quantity']."'
                        class='form-control quantity' id='quantity$id' onchange='updateCart($id)' />
                </td>
                <td data-th='Subtotal' class='text-center'>Rp.".$details['price'] * $details['quantity']."</td>
                <td class='actions' data-th=''>
                    <button class='btn btn-danger btn-sm remove-from-cart' onclick='deleteCart($id)'><i class='fa fa-trash-o'></i></button>
                </td>
                </tr>";
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
