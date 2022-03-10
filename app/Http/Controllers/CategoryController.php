<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array(
            'shopName' => Setting::where('name', 'shop-name')->first()->value,
            'categories' => Category::all()
        );
        return view('category.index', $data);
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
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        // return $category->slug;
    }

    public function showProduct($slug)
    {
        if (request('search')) {
            $search = request('search');
            $products = DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->selectRaw('products.name, products.image, products.price, products.id')
                ->where('categories.slug', '=', $slug)
                ->where('products.name', 'like', "%$search%")
                ->orderByDesc('products.created_at')
                ->paginate(8);
        } else {
            $products = DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->selectRaw('products.name, products.image, products.price, products.id')
                ->where('categories.slug', '=', $slug)
                ->orderByDesc('products.created_at')
                ->paginate(8);
        }

        $data = array(
            'products' => $products,
            'shopName' => Setting::where('name', 'shop-name')->first()->value,
            'slug' => $slug
        );
        return view('product.index', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
