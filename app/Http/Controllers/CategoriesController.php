<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $no = 5;
        $data = array(
            'categories' => Category::paginate($no),
            'url' => 'categories' 
        );
        return view('categories.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array(
            'url' => 'categories' 
        );
        return view('categories.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'slug' => 'required|unique:categories',
            'image' => 'image|file|max:1024'
        );
        $validate = $request->validate($rules);
        if ($request->file('image')) {
            $validate['image'] = $request->file('image')->store('category-images');
        }

        Category::create($validate);
        return redirect('/categories')->with('success', 'Category has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        // dd($category);
    }
    
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $data = array(
            'category' => $category,
            'url' => 'categories'
        );
        return view('categories.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $rules = array(
            'name' => 'required',
            'slug' => 'required|unique:categories',
            'image' => 'image|file|max:1024'
        );
        
        if ($request->slug != $category->slug) {
            $rules['slug'] = 'required|unique:categories';
        }else{
            $rules['slug'] = 'required';
        }
        $validate = $request->validate($rules);

        if ($request->file('image')) {
            if ($request->oldImage) {
                Storage::delete($request->oldImage);
            }
            $validate['image'] = $request->file('image')->store('category-images');
        }
        Category::where('id', $category->id)
                    ->update($validate);
        return redirect('categories/'.$category->id.'/edit')->with('success', 'Category has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        Category::destroy($category->id);
        return redirect('categories')->with('success', 'Category has been deleted');
    }
}
