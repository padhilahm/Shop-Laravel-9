<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $no = 5;
        $product = DB::table('products')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->selectRaw('products.name, products.price, products.id, categories.name as category')
                    ->orderBy('products.created_at', 'desc')
                    ->paginate($no);

        $data = array(
            'products' => $product, 
            'url' => 'products',
            'no' => $no
        );
        return view('products.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array(
            'categories' => Category::all(),
            'url' => 'products'
        );
        return view('products.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'image' => 'image|file|max:1024',
            'category_id' => 'required'
        ];
        $validate = $request->validate($rules);
        if ($request->file('image')) {
            $validate['image'] = $request->file('image')->store('product-images');
        }

        Product::create($validate);
        return redirect('/products')->with('success', 'Product has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $product = DB::table('products')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->selectRaw('products.name, products.description, products.price, products.id, categories.name as category_name, categories.id as category_id, products.image')
                    ->whereRaw("products.id = $product->id")
                    ->first();
        $categories = DB::table('categories')
                        ->get();
        $data = array(
            'product' => $product,
            'categories' => $categories,
            'url' => 'products'
        );
        return view('products.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $rules = [
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'image' => 'image|file|max:1024',
            'category_id' => 'required'
        ];
        $validate = $request->validate($rules);
        if ($request->file('image')) {
            if ($request->oldImage) {
                Storage::delete($request->oldImage);
            }
            $validate['image'] = $request->file('image')->store('product-images');
        }

        Product::where('id', $product->id)
                ->update($validate);
        return redirect('/products')->with('success', 'Product has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        Product::destroy($product->id);
        return redirect('products')->with('success', 'Product has been deleted');
    }
}
